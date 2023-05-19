<?php
namespace App\Services;

use App\Http\Controllers\AppBaseController;
use App\Packaging;
use \Ups\Entity\Shipment;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class UPSService extends AppBaseController
{
    public function __construct()
    {

    }

    public function cotizarEnvio($codigoPais, $codigoPostal, $calle, $ciudad, $productos)
    {
        $rate = new \Ups\Rate(config('ups.UPS_ACCESS_KEY'), config('ups.UPS_USERID'), config('ups.UPS_PASSWORD'), config('ups.UPS_INTEGRATION'));
        try {
            $shipment = new \Ups\Entity\Shipment();

            $shipper = $shipment->getShipper();
            $shipper->setShipperNumber(config('ups.UPS_USERID'));
            $shipper->setName(config('ups.DIRECCION_DESDE.NOMBRE'));
            $shipper->setAttentionName(config('ups.DIRECCION_DESDE.NOMBRE'));
            $shipperAddress = $shipper->getAddress();
            $shipperAddress->setAddressLine1(config('ups.DIRECCION_DESDE.DIRECCION'));
            $shipperAddress->setPostalCode(config('ups.DIRECCION_DESDE.CODIGO_POSTAL'));
            $shipperAddress->setCity(config('ups.DIRECCION_DESDE.PROVINCIA'));
            $shipperAddress->setCountryCode(config('ups.DIRECCION_DESDE.PAIS'));
            $shipper->setAddress($shipperAddress);
            $shipper->setEmailAddress(config('ups.EMAIL'));
            $shipper->setPhoneNumber(config('ups.TELEFONO'));
            $shipment->setShipper($shipper);

            $address = new \Ups\Entity\Address();
            $address->setAttentionName(config('ups.DIRECCION_DESDE.NOMBRE'));
            $address->setAddressLine1(config('ups.DIRECCION_DESDE.DIRECCION'));
            $address->setCity(config('ups.DIRECCION_DESDE.PROVINCIA'));
            $address->setCountryCode(config('ups.DIRECCION_DESDE.PAIS'));
            $address->setPostalCode(config('ups.DIRECCION_DESDE.CODIGO_POSTAL'));

            $shipFrom = new \Ups\Entity\ShipFrom();
            $shipFrom->setAddress($address);

            $shipment->setShipFrom($shipFrom);

            $address = new \Ups\Entity\Address();
            $address->setAttentionName('Hasta');
            $address->setAddressLine1($calle);
            $address->setCity($ciudad);
            $address->setCountryCode($codigoPais);
            $address->setPostalCode($codigoPostal);

            $shipTo = new \Ups\Entity\ShipTo();
            $shipTo->setAddress($address);

            $shipment->setShipTo($shipTo);

            $cajasEnvio = $this->calcularCajas($productos);
            $peso = 0;
            foreach($cajasEnvio as $caja)
            {
                $package = new \Ups\Entity\Package();
                $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
                $package->getPackageWeight()->setWeight($caja->peso);
                $peso += $caja->peso;
                // if you need this (depends of the shipper country)
                $weightUnit = new \Ups\Entity\UnitOfMeasurement;
                $weightUnit->setCode("KGS");
                $package->getPackageWeight()->setUnitOfMeasurement($weightUnit);

                $dimensions = new \Ups\Entity\Dimensions();
                $dimensions->setHeight($caja->alto);
                $dimensions->setWidth($caja->ancho);
                $dimensions->setLength($caja->largo);

                $unit = new \Ups\Entity\UnitOfMeasurement;
                $unit->setCode("CM");

                $dimensions->setUnitOfMeasurement($unit);
                $package->setDimensions($dimensions);

                $shipment->addPackage($package);
            }

            $service = new \Ups\Entity\Service;
            $service->setCode(\Ups\Entity\Service::S_SAVER);
            $service->setDescription($service->getName());
            $shipment->setService($service);

            $rateInformation = new \Ups\Entity\RateInformation;
            $rateInformation->setNegotiatedRatesIndicator(1);
            $rateInformation->setRateChartIndicator(1);
            $shipment->setRateInformation($rateInformation);
            $resultado = $rate->getRate($shipment);

            $dolarOficial = obtenerDolarUPS();
            return [
                'cotizacion' => $dolarOficial,
                'pesos' => ($resultado->RatedShipment[0]->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue * $dolarOficial),
                'dolares' => $resultado->RatedShipment[0]->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue
            ];
        } catch (Exception $e) {
            throw $e;
            //var_dump($e->getMessage());
        }
    }

    public function calcularCajas($productos)
    {
        $cajas = [];
        if(count($productos) > 6) {
            $resto = count($productos) % 6;
            $cajas = $this->obtenerCajasPorUnidad($productos, 6);
            if($resto > 0) {
                $cajas = $cajas->push($this->obtenerCajaIndividual($productos)[0]);
            }
        } else {
            $cajas = $this->obtenerCajaIndividual($productos);
        }

        return $cajas;
    }

    public function obtenerCajaIndividual($productos)
    {
        if (count($productos) > 1) {
            $caja = Packaging::where('unidades', '>=', count($productos))->orderBy('unidades')->take(1)->get();
        } else{
            $caja = Packaging::where('unidades', 2)->orderBy('unidades')->take(1)->get();
        }

        foreach($productos as $producto)
        {
            $caja[0]->peso += (float) ($producto->vino ? $producto->vino->peso : $producto->aniada->vino->peso);
        }

        return $caja;
    }

    public function obtenerCajasPorUnidad(&$productos, $unidad)
    {
        $cantidadCajas = count($productos) / $unidad;

        $cajas = Packaging::where('unidades', $unidad)->get();
        for($x = 0; $x < intval($cantidadCajas); $x++)
        {
            if($x > 0) {
                $cajaIndividual = Packaging::where('unidades', $unidad)->first();
                $cajas->push($cajaIndividual);
            }
        }

        $unidadesUsadas = 0;
        $llenoCaja = 0;
        foreach($productos as $key => $producto)
        {
            $cajas[$llenoCaja]->peso += (float) $producto->vino->peso;
            unset($productos[$key]);
            $unidadesUsadas++;

            if($unidadesUsadas == $unidad) {
                $llenoCaja++;
                $unidadesUsadas = 0;
                if($llenoCaja >= intval($cantidadCajas)) {
                    break;
                }
            }
        }

        return $cajas;
    }

    public function generarEnvio($numeroOrden, $productos, $direccion, $ciudad, $codigoPais, $codigoPostal, $nombreDestinatario, $apellidoDestinatario, $emailDestinatario, $telefonoDestinatario)
    {
        $shipment = new \Ups\Entity\Shipment();

        $shipper = $shipment->getShipper();
        $shipper->setShipperNumber(config('ups.UPS_USERID'));
        $shipper->setName(config('ups.DIRECCION_DESDE.NOMBRE'));
        $shipper->setAttentionName(config('ups.DIRECCION_DESDE.NOMBRE'));
        $shipperAddress = $shipper->getAddress();
        $shipperAddress->setAddressLine1(config('ups.DIRECCION_DESDE.DIRECCION'));
        $shipperAddress->setPostalCode(config('ups.DIRECCION_DESDE.CODIGO_POSTAL'));
        $shipperAddress->setCity(config('ups.DIRECCION_DESDE.PROVINCIA'));
        $shipperAddress->setCountryCode(config('ups.DIRECCION_DESDE.PAIS'));
        $shipper->setAddress($shipperAddress);
        $shipper->setEmailAddress(config('ups.EMAIL'));
        $shipper->setPhoneNumber(config('ups.TELEFONO'));
        $shipment->setShipper($shipper);

        $address = new \Ups\Entity\Address();
        $address->setAddressLine1($direccion);
        $address->setCity($ciudad);
        $address->setCountryCode($codigoPais);
        $address->setPostalCode($codigoPostal);
        $shipTo = new \Ups\Entity\ShipTo();
        $shipTo->setAddress($address);
        $shipTo->setCompanyName($nombreDestinatario . ' ' . $apellidoDestinatario);
        $shipTo->setAttentionName($nombreDestinatario . ' ' . $apellidoDestinatario);
        $shipTo->setEmailAddress($emailDestinatario);
        $shipTo->setPhoneNumber($telefonoDestinatario);
        $shipment->setShipTo($shipTo);

        $address = new \Ups\Entity\Address();
        $address->setAddressLine1(config('ups.DIRECCION_DESDE.DIRECCION'));
        $address->setPostalCode(config('ups.DIRECCION_DESDE.CODIGO_POSTAL'));
        $address->setCity(config('ups.DIRECCION_DESDE.PROVINCIA'));
        $address->setCountryCode(config('ups.DIRECCION_DESDE.PAIS'));
        $shipFrom = new \Ups\Entity\ShipFrom();
        $shipFrom->setAddress($address);
        $shipFrom->setName(config('ups.DIRECCION_DESDE.NOMBRE'));
        $shipFrom->setAttentionName($shipFrom->getName());
        $shipFrom->setCompanyName($shipFrom->getName());
        $shipFrom->setEmailAddress(config('ups.EMAIL'));
        $shipFrom->setPhoneNumber(config('ups.TELEFONO'));
        $shipment->setShipFrom($shipFrom);

        $address = new \Ups\Entity\Address();
        $address->setAddressLine1($direccion);
        $address->setCity($ciudad);
        $address->setCountryCode($codigoPais);
        $address->setPostalCode($codigoPostal);
        $soldTo = new \Ups\Entity\SoldTo;
        $soldTo->setAddress($address);
        $soldTo->setAttentionName($nombreDestinatario . ' ' . $apellidoDestinatario);
        $soldTo->setCompanyName($soldTo->getAttentionName());
        $soldTo->setEmailAddress($emailDestinatario);
        $soldTo->setPhoneNumber($telefonoDestinatario);
        $shipment->setSoldTo($soldTo);

        $service = new \Ups\Entity\Service;
        $service->setCode(\Ups\Entity\Service::S_SAVER);
        $service->setDescription($service->getName());
        $shipment->setService($service);

        $shipment->setDescription('Orden - '.$numeroOrden);

        $cajasEnvio = $this->calcularCajas($productos);

        foreach($cajasEnvio as $caja)
        {
            $package = new \Ups\Entity\Package();
            $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
            $package->getPackageWeight()->setWeight($caja->peso);
            $weightUnit = new \Ups\Entity\UnitOfMeasurement;
            $weightUnit->setCode("KGS");
            $package->getPackageWeight()->setUnitOfMeasurement($weightUnit);

            $dimensions = new \Ups\Entity\Dimensions();
            $dimensions->setHeight($caja->alto);
            $dimensions->setWidth($caja->ancho);
            $dimensions->setLength($caja->largo);

            $unit = new \Ups\Entity\UnitOfMeasurement;
            $unit->setCode("CM");

            $dimensions->setUnitOfMeasurement($unit);
            $package->setDimensions($dimensions);
            $package->setDescription('Vinos - FRAGIL');

            $shipment->addPackage($package);
        }

        $referenceNumber = new \Ups\Entity\ReferenceNumber;
        $referenceNumber->setCode(\Ups\Entity\ReferenceNumber::CODE_INVOICE_NUMBER);
        $referenceNumber->setValue($numeroOrden);
        $shipment->setReferenceNumber($referenceNumber);

        //dd($shipment);
        $shipment->setPaymentInformation(new \Ups\Entity\PaymentInformation('prepaid', (object) ['AccountNumber' => $shipper->getShipperNumber()]));

        // Ask for negotiated rates (optional)
        $rateInformation = new \Ups\Entity\RateInformation;
        $rateInformation->setNegotiatedRatesIndicator(true);
        $shipment->setRateInformation($rateInformation);

        $dolarOficial = obtenerDolarUPS();

        $respuesta = [];
        // Get shipment info
        try {
            $api = new \Ups\Shipping(config('ups.UPS_ACCESS_KEY'), config('ups.UPS_USERID'), config('ups.UPS_PASSWORD'), config('ups.UPS_INTEGRATION'));

            $confirm = $api->confirm(\Ups\Shipping::REQ_VALIDATE, $shipment);

                logger(json_encode($confirm));
                logger($confirm->ShipmentCharges->TotalCharges->MonetaryValue); // Confirm holds the digest you need to accept the result
                logger($confirm->ShipmentIdentificationNumber); // Confirm holds the digest you need to accept the result

            if ($confirm) {
                $accept = $api->accept($confirm->ShipmentDigest);
                $respuesta = [
                    'tracking_number' => $confirm->ShipmentIdentificationNumber,
                    'digest' => $confirm->ShipmentDigest,
                    'etiqueta' => $accept->PackageResults->LabelImage->GraphicImage,
                    'cotizacion_usd' => $dolarOficial,
                    'pesos' => ($confirm->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue * $dolarOficial)  * 1.21,
                    'dolares' => $confirm->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue,
                ];

                //logger(json_decode( json_encode($accept), true));
                 // Accept holds the label and additional information
                /*$label_file = $numeroOrden. ".jpg";

                $base64_string = $accept->PackageResults->LabelImage->GraphicImage;

                $ifp = fopen(public_path('etiquetas/'.$label_file), 'wb');

                fwrite($ifp, base64_decode($base64_string));

                fclose($ifp);*/
            }

            return $respuesta;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
