<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Services\UPSService;
use Illuminate\Http\Request;
use App\Repositories\PedidoRepository;
use App\Http\Requests\Admin\CUPedidoRequest;
use App\Repositories\Criteria\PedidosCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class PedidoController extends CrudAdminController
{
    protected $routePrefix = 'pedidos';
    protected $viewPrefix  = 'admin.pedidos.';
    protected $actionPerms = 'pedidos';

    public function __construct(PedidoRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['filters']['despacho'] = null;
        $this->data['filters']['tipo_factura'] = null;
        $this->data['filters']['estado_id'] = null;
        $this->data['filters']['estatus_1'] = null;
        $this->data['filters']['estatus_2'] = null;
        $this->data['filters']['estatus_3'] = null;
        $this->data['filters']['export_xls'] = true;
        $this->data['url_generar_envio'] = route('pedidos.generar-envio',['_ID_']);
        $this->data['url_ver_etiqueta'] = route('pedidos.ver-etiqueta',['_ID_']);
        $this->data['url_save'] = route($this->routePrefix.'.update',['_ID_']);
        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter(Request $request)
    {
        $data = $this->_filter($request, false);

        return $this->sendResponse($data, trans('admin.success'));
    }

    protected function _filter($request,$export=false) {
        try
        {
            $this->repository->pushCriteria(new PedidosCriteria($request));
            $this->repository->pushCriteria(new RequestCriteria($request));

            if ($export) {
                $data = $this->repository->with("items.aniada.vino")->all();

            } else {
                $collection = $this->repository->paginate($request->get('per_page'))->toArray();

                $data = [
                    'list' => $collection['data'],
                    'paging' => \Arr::only($collection,['total','current_page','last_page'])
                ];
            }

            return $data;

        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(),500);
        }
    }

    public function exportXls(Request $request)
    {


        $collection = $this->_filter($request,true);
        $data = [];
        foreach ($collection as $item) {
            foreach ($item->items as $linea) {
                $data[] = [
                    "id" => $item->id,
                    "despacho" => $item->despacho,
                    "cliente" => $item->nombre . " " . $item->apellido,
                    "tipo_factura" => $item->tipo_factura,
                    "estado" => $item->estado,
                    "created_at" => $item->created_at,
                    "vino" => $linea->aniada && $linea->aniada->vino ? $linea->aniada->vino->titulo : "",
                    "aniada" => $linea->aniada && $linea->aniada->vino ? $linea->aniada->anio : "",
                    "cantidad" => $linea->cantidad,
                    "precio_unitario" => $linea->precio_pesos,
                    "total" => $item->total,
                    "total_envio" => $item->total_envio,
                    "sincronizo_sap" => $item->sincronizo_sap,
                    "documento_sap" => $item->documento_sap,
                    "error_sincronizacion_sap" => $item->error_sincronizacion_sap,
                    "email" => $item->email,
                    'direccion' => $item->direccion,
                    'ciudad' => $item->ciudad,
                    'cp' => $item->cp,
                    'provincia' => $item->provincia,
                    'departamento' => $item->departamento,
                    'info_adicional' => $item->info_adicional,
                    'dni' => $item->dni,
                    'nombre_fc' => $item->nombre_fc,
                    'apellido_fc' => $item->apellido_fc,
                    'dni_fc' => $item->dni_fc,
                    'direccion_fc' => $item->direccion_fc,
                    'ciudad_fc' => $item->ciudad_fc,
                    'cp_fc' => $item->cp_fc,
                    'provincia_fc' => $item->provincia_fc,
                    'pais_id_fc' => $item->pais ? $item->pais->nombre : "",
                    'comentarios' => $item->comentarios
                ];
            }
        }


        $name = 'Pedidos';
        $header = [
            'id' => 'Nro',
            'despacho' => 'Despacho',
            'created_at' => 'Fecha',
            'cliente' => 'Cliente',
            'dni' => 'DNI',
            'email' => 'Email',
            'tipo_factura' => 'Tipo Factura',
            'estado' => 'Estado',

            'sap' => 'SAP',


            'direccion' => "Direccion",
            'ciudad' => "Ciudad",
            'cp' => "CP",
            'provincia' => "Provincia",
            'departamento' => "Departamento",
            'info_adicional' => "Info Adicional",

            'nombre_fc' => "Nombre FC",
            'apellido_fc' => "Apellido FC",
            'dni_fc' => "DNI FC",
            'direccion_fc' => "Direccion FC",
            'ciudad_fc' => "Ciudad FC",
            'cp_fc' => "CP FC",
            'provincia_fc' => "Provincia FC",
            'pais_id_fc' => "Pais FC",


            'vino' => 'Vino',
            'aniada' => 'Añada',
            'cantidad' => 'Cantidad',
            'precio_unitario' => 'Precio Unitario',
            'subtotal' => 'Subtotal',
            'total_envio' => 'Costo envío',
            'total' => 'Total',
            'comentarios' => 'Comentarios'
        ];
        $format = [
            'precio_unitario' => function($col,$row) {
                return formatoImporte($col,"$");
            },
            'total' => function($col,$row) {
                return formatoImporte($col,"$");
            },
            'subtotal' => function($col,$row) {
                return formatoImporte(($row["cantidad"] * $row["precio_unitario"]),"$");
            },
            'total_envio' => function($col,$row) {
                return formatoImporte($col,"$");
            },
            'sap' => function($col,$row) {
                if ($row["sincronizo_sap"]) {
                    if ($row["documento_sap"]) {
                        return "Nro ".$row["documento_sap"];
                    } else {
                        return "SI - Sin Nro.";
                    }

                    if ($row["error_sincronizacion_sap"]) {
                        return $row["error_sincronizacion_sap"];
                    }
                } else {
                    if ($row["error_sincronizacion_sap"]) {
                        return $row["error_sincronizacion_sap"];
                    }
                }
                return "";
            }
        ];
        return $this->_exportXls($data,$header,$format,$name);
    }

    public function show($id)
    {
        parent::show($id);

        //$this->data['selectedItem']->load('xxx');

        return view($this->viewPrefix.'show')->with('data', $this->data);
    }

    public function create()
    {
        parent::create();

        data_set($this->data, 'selectedItem', [
                'id' => 0
        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUPedidoRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);
        $this->data['selectedItem']->load(['registrado','pais','items.aniada.vino']);
        $this->data['selectedItem']->estatus_1 = true;
        $this->data['selectedItem']->save();
        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUPedidoRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }

    public function generarEnvio($id, Request $request, UPSService $upsService)
    {
        try {
            $model = $this->repository->with(['items.aniada.vino'])->findWithoutFail($id);

            $productos = [];
            foreach ($model->items as $item) {
                for($x=0; $x < $item->cantidad; $x++) {
                    array_push($productos, $item);
                }
            }


            $direccionUps = [];
            $direccionUps[] = $model->direccion;
            if ($model->departamento) {
                $direccionUps[] = $model->departamento;
            }
            if ($model->info_adicional) {
                $direccionUps[] = $model->info_adicional;
            }

            $respuesta = $upsService->generarEnvio($id
                ,$model->items
                ,$direccionUps
                ,$model->ciudad
                ,$model->pais->codigo
                ,$model->cp
                ,$model->nombre
                ,$model->apellido
                ,$model->email
                ,''
            );



            $model->ups_tracking_number = $respuesta['tracking_number'];
            $model->ups_etiqueta = $respuesta['etiqueta'];
            $model->ups_info = \Arr::except($respuesta,['tracking_number','etiqueta']);
            $model->save();

            return $this->sendResponse($respuesta,trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(),500);
        }

    }

    public function verEtiqueta($id, Request $request)
    {
        try {
            $model = $this->repository->find($id,['ups_etiqueta','estado_id','id']);
            $raw_image_string = base64_decode($model->ups_etiqueta);
            return response($raw_image_string)->header('Content-Type', 'image/jpg');



            return $this->sendResponse($respuesta,trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(),500);
        }

    }

}
