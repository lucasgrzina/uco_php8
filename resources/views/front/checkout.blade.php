@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        _methods.confirmarSeccion = function(seccion) {
            var _this = this;
            var siguiente = 'envioRetiro';
            var form = this.checkout.form;
            switch(seccion) {
                case 'datosContacto':
                    if (!this.validarDatosContacto(form)){
                        return false;
                    }
                    console.debug(this.checkout.direcciones.listado.length);
                    if (this.checkout.direcciones.listado.length > 0) {
                        this.seleccionarDireccion(0);
                    }
                    break;
                case 'envioRetiro':

                    siguiente = 'datosDestinatario';
                    break;
                case 'datosDestinatario':
                if (!this.validarDatosDestinatario(form)){
                        return false;
                    }
                    siguiente = 'datosFacturacion';
                    break;
                case 'datosFacturacion':
                    if (!this.validarDatosFacturacion(form)){
                        return false;
                    }
                    siguiente = 'confirmarPedido';

                    break;
            }

            if (seccion === 'confirmarPedido') {
                Vue.set(_this.checkout.form,'envio_retiro_id',_this.checkout.direcciones.listado[0].id);
                _this.checkoutConfirmar();
            } else {
                this.checkout.seccionActual = siguiente;
                this.checkout.secciones[siguiente] = true;
            }

        }

        _methods.editarSeccion = function(seccion) {
            this.checkout.seccionActual = seccion;
        }

        _methods.validarDatosContacto = function (form) {
            if (!form.email) {
                this.toastError('Ingrese su email');
                return false;
            }
            return true;
        }

        _methods.validarDatosFacturacion = function (form) {
            console.debug(form);
            console.debug(this.checkout.info);
            if (form.tipo_factura == 'A') {
                if (!form.razon_social || !form.cuit || !form.direccion_fc || !form.ciudad_fc || !form.provincia_fc || !form.cp_fc) {
                    this.toastError('Complete los datos de facturación');
                    return false;
                }

            } else {
                if (!form.nombre_fc || !form.apellido_fc || !form.dni_fc) {
                    this.toastError('Complete los datos de facturación');
                    return false;
                }
                if (form.total >= this.checkout.info.montoDatosFC) {
                    if (!form.direccion_fc || !form.ciudad_fc || !form.provincia_fc || !form.cp_fc) {
                        this.toastError('Complete los datos de facturación');
                        return false;
                    }
                }
            }
            return true;
        }

        _methods.validarDatosDestinatario = function (form) {
            if (!form.nombre || !form.apellido || !form.dni) {
                this.toastError('Complete los datos del destinatario');
                return false;
            }
            return true;
        }


        _methods.agregarDireccion = function () {
            var _seccion = this.checkout.direcciones;
            _seccion.itemSeleccionado = _.cloneDeep(this.checkout.modelos.direccion);
            Vue.nextTick(function() {
                var scrollDiv = document.getElementById("formDomicilio").offsetTop - 100;
                window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
            });
        }

        _methods.editarDireccion = function (item, index) {
            var _seccion = this.checkout.direcciones;
            _seccion.itemSeleccionado = _.cloneDeep(item);

            Vue.nextTick(function() {
                var scrollDiv = document.getElementById("formDomicilio").offsetTop - 100;
                window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
            });
        }

        _methods.seleccionarDireccion = function(index) {
            var _seccion = this.checkout.direcciones;
            var _this = this;
            var _listado = _.cloneDeep(_seccion.listado);

            _.each(_listado, function (item) {
                Vue.set(item,'principal',false);
            });

            Vue.set(_listado[index],'principal',true);


            _seccion.listado = _.orderBy(_listado,['principal'],['desc']);

            //Vue.set(_seccion,'itemSeleccionado',_listado[index]);

            Vue.nextTick(function() {
                _this.cotizarEnvio(0);
            });


        }

        _methods.cotizarEnvio = function(index) {
            var _seccion = this.checkout;
            var _this = this;

            _seccion.cotizando_envio = true;
            _this._call(_seccion.url_cotizar_envio,'POST',_seccion.direcciones.listado[index]).then(function(data) {
                console.debug(data);
                _seccion.form = _.assign(_seccion.form,{
                    total_envio: data.pesos,
                    total_envio_usd: data.dolares,
                    cotizacion_usd: data.cotizacion
                });
                _this.calcTotal();
                _seccion.cotizando_envio = false;
            }, function(error) {
                _seccion.cotizando_envio = false;
                //console.debug(error.data.message);
                alert2(error.data.message);
            });
        }


        _methods.guardarDireccion = function () {
            var _seccion = this.checkout.direcciones;
			var _this = this;
			var _errorMsg = null;
            var _esEdicion = _seccion.itemSeleccionado.id > 0;

            _seccion.enviando = true;
            _this._call(_seccion.url_guardar,'POST',_seccion.itemSeleccionado).then(function(data) {

                console.debug(data);
                //Si es una edicion, reemplazo la primera posicion
                if (_esEdicion) {
                    Vue.set(_seccion.listado,0,_.cloneDeep(_seccion.itemSeleccionado));
                } else {
                    _seccion.itemSeleccionado.id = data.id;
                    var _listado = _.cloneDeep(_seccion.listado);

                    _.each(_listado, function (item) {
                        Vue.set(item,'principal',false);
                    });
                    //_seccion.itemSeleccionado.seleccionada = true;
                    _listado.unshift(_seccion.itemSeleccionado);
                    _seccion.listado = _.orderBy(_listado,['principal'],['desc']);
                    Vue.nextTick(function() {
                        _this.cotizarEnvio(0);
                    });
                }

                _this.cerrarDireccion();
                // _seccion.itemSeleccionado = _.cloneDeep(_this.checkout.modelos.direcciones);
                _seccion.enviando = false;
            }, function(error) {
                _seccion.enviando = false;
                if (error.status != 422) {
                    //console.debug(error.data.message);
                    alert2(error.data.message);
                } else {
                    var mensaje = [];
                    _.forEach(error.fields, function(msj,campo) {
                        mensaje.push(msj[0]);
                    });
                    alert2(mensaje[0]);
                }


            });


        }

        _methods.cerrarDireccion = function () {
            var _seccion = this.checkout.direcciones;
			var _this = this;
			this.checkout.direcciones.itemSeleccionado = _.cloneDeep(_this.checkout.modelos.direcciones);
            var scrollDiv = document.getElementById("seccionEnvio").offsetTop - 150;
            window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
        }

        _methods.alCambiarPais = function() {
            var _this = this;
            _this.checkout.direcciones.itemSeleccionado.pais = _.find(_this.checkout.info.paises, {
                id: _this.checkout.direcciones.itemSeleccionado.pais_id
            })
        }
        _methods.alCambiarUsarDatosDest = function() {
            var _this = this;
            if (_this.checkout.form.usarDatosDest) {
                _this.checkout.form.nombre_fc = _this.checkout.form.nombre;
                _this.checkout.form.apellido_fc = _this.checkout.form.apellido;
                _this.checkout.form.dni_fc = _this.checkout.form.dni;
            } else {
                _this.checkout.form.nombre_fc = null;
                _this.checkout.form.apellido_fc = null;
                _this.checkout.form.dni_fc = null;
            }
        }


        _methods.seleccionarTipoFactura = function() {
            var _this = this;
            //_this.checkout.form.tipo_factura = tipo;
            var tipo = _this.checkout.form.tipo_factura;
            console.debug(tipo);
            if (tipo == 'CF') {
                _this.checkout.form = _.assign(_this.checkout.form,{
                    //tipo_factura: tipo,
                    razon_social: null,
                    cuit: null,
                    tipo_factura_desc: 'Consumidor final'
                });
            } else {
                _this.checkout.form = _.assign(_this.checkout.form,{
                    /*tipo_factura: tipo,
                    nombre: null,
                    apellido: null,
                    dni: null,*/
                    tipo_factura_desc: 'Factura A'
                });
            }
        }

        _methods.calcTotal = function () {
            if (this.locale == 'es') {
                this.checkout.form.total = this.carrito.total + this.checkout.form.total_envio;
            } else {
                this.checkout.form.total = this.carrito.total + this.checkout.form.total_envio;
                // this.checkout.form.total_usd = this.carrito.total + this.checkout.form.total_envio_usd;
            }


        }

        this._mounted.push(function(_this) {
            _this.checkout.form.total = _this.carrito.total;
            _this.checkout.form.total_usd = _this.carrito.total;
        });
    </script>
@endsection
@php
    //$actual = $data['actual'];
@endphp
@section('content')
<section class="section-checkout" >

	<div class="container " >
		<div class="row">

			<div class="col-md-8 col-checkout-1">

				<!-- DATOS DE CONTACTO -->

				<div class="block-content" v-if="checkout.secciones.datosContacto">
					<h2>{{trans('front.paginas.checkout.datosContacto.titulo')}}</h2>
					<form>
						<!-- Email input -->
						<fieldset :disabled="checkout.seccionActual !== 'datosContacto'">
                            <div class="form-floating  mb-form">
                                <input type="email" class="form-control" id="emailInput" placeholder="{{trans('front.paginas.checkout.datosContacto.form.emailPlaceholder')}}*" v-model="checkout.form.email">
                                <label for="emailInput">{{trans('front.paginas.checkout.datosContacto.form.emailPlaceholder')}}*</label>
                            </div>

                            <div class="form-check " style="margin-bottom: 10px;">
                                <label class="container">{{trans('front.paginas.checkout.datosContacto.form.recibir')}}
                                    <input type="checkbox" checked="checked" name="terminos-condiciones" v-model="checkout.form.recibir">
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <button type="button" class="btn btn-primary f-right" @click="confirmarSeccion('datosContacto')" v-show="checkout.seccionActual === 'datosContacto'">{{trans('front.paginas.checkout.btnConfirmar')}}</button>
                        </fieldset>
                        <button type="button" class="btn btn-primary f-right" @click="editarSeccion('datosContacto')" v-show="checkout.seccionActual === 'envioRetiro'">{{trans('front.paginas.checkout.btnEditar')}}</button>

					</form>
				</div>

				<!-- END DATOS DE CONTACTO -->


				<!-- DOMICILIO -->
				<template v-if="checkout.secciones.envioRetiro">

                            <div class="block-content" id="seccionEnvio">
                                <h2>{{trans('front.paginas.checkout.datosEnvio.titulo')}}</h2>
                                <form>
                                    <fieldset :disabled="checkout.seccionActual !== 'envioRetiro'">
                                        <a href="javascript:void(0)" v-show="checkout.seccionActual === 'envioRetiro'" class="f-right mb-3 lnk-agregar" @click="agregarDireccion()">{{trans('front.paginas.checkout.btnAgregar')}}</a>
                                        <template v-for="(item,index) in checkout.direcciones.listado">
                                            <div class="direccion" v-show="index === 0 || checkout.seccionActual === 'envioRetiro'">
                                                <div class="info">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                    <p><span>(% item.calle %)</span><span>(% item.cp %),(% item.ciudad %)</span><span>(% item.nombre.concat(' ').concat(item.apellido)%)</span></p>
                                                </div>
                                                <div class="actions" v-if="checkout.seccionActual == 'envioRetiro'">
                                                    <a href="javascript:void(0)" v-show="index > 0" class="f-right" @click="seleccionarDireccion(index)">{{trans('front.paginas.checkout.btnSeleccionar')}}</a>
                                                    <a href="javascript:void(0)" v-show="index == 0" class="f-right" @click="editarDireccion(item,index)">{{trans('front.paginas.checkout.btnEditar')}}</a>
                                                    <!--button type="button" v-show="index > 0" class="btn btn-primary f-right" @click="seleccionarDireccion(index)">Seleccionar</button>
                                                    <button type="button" v-show="index == 0" class="btn btn-primary f-right" @click="editarDireccion(item,index)">Editar</button-->
                                                </div>

                                            </div>
                                        </template>
                                        <!--div class="direccion">
                                            <div class="info">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <p><span>Libertador 1234</span><span>CP XXXX,Belgrano - Capital Federal</span><span>Juan Perez - 1167896001</span></p>
                                            </div>
                                            <div class="actions">
                                                <button class="btn btn-primary f-right">Editar o elegir otro</button>
                                            </div>

                                        </div-->
                                        <button v-if="checkout.direcciones.listado.length > 0 && !checkout.direcciones.itemSeleccionado" type="button" class="btn btn-primary f-right" @click="confirmarSeccion('envioRetiro')" v-show="checkout.seccionActual === 'envioRetiro'">{{trans('front.paginas.checkout.btnConfirmar')}}</button>
                                    </fieldset>
                                    <button type="button" class="btn btn-primary f-right" @click="editarSeccion('envioRetiro')" v-show="checkout.seccionActual == 'datosDestinatario'">{{trans('front.paginas.checkout.btnEditar')}}</button>
                                </form>
                            </div>

                            <!-- END DOMICILIO -->

                            <!-- DESTINATARIO -->

                            <div id="formDomicilio" class="block-content" v-if="checkout.direcciones.itemSeleccionado">

                                <h2>{{trans('front.paginas.checkout.datosEnvio.domicilioDest')}}</h2>
                                <form>
                                    <!-- Email input -->
                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.direcciones.itemSeleccionado.nombre" placeholder="{{trans('front.paginas.checkout.datosEnvio.nombre')}}*" maxlength="15">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosEnvio.nombre')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.direcciones.itemSeleccionado.apellido" placeholder="{{trans('front.paginas.checkout.datosEnvio.apellido')}}*" maxlength="25">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosEnvio.apellido')}}*</label>
                                    </div>


                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.direcciones.itemSeleccionado.calle" placeholder="{{trans('front.paginas.checkout.datosEnvio.calle')}}*" maxlength="35">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosEnvio.calle')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.direcciones.itemSeleccionado.departamento" placeholder="{{trans('front.paginas.checkout.datosEnvio.departamento')}}" maxlength="35">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosEnvio.departamento')}}</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.direcciones.itemSeleccionado.ciudad" placeholder="{{trans('front.paginas.checkout.datosEnvio.ciudad')}}*" maxlength="30">
                                        <label for="ciudadInput">{{trans('front.paginas.checkout.datosEnvio.ciudad')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.direcciones.itemSeleccionado.provincia" placeholder="{{trans('front.paginas.checkout.datosEnvio.provincia')}}*" maxlength="35">
                                        <label for="provinciaInput">{{trans('front.paginas.checkout.datosEnvio.provincia')}}*</label>
                                    </div>

                                    <!--div class="form-floating  mb-form">
                                        <select class="form-control"  v-model="checkout.direcciones.itemSeleccionado.pais_id" @change="alCambiarPais()">
                                            <option :value="null">Pais</option>
                                            <option v-for="item in checkout.info.paises" :value="item.id">(% item.nombre %)</option>
                                        </select>
                                        <label for="provinciaInput">Pais*</label>
                                    </div-->

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.direcciones.itemSeleccionado.cp" placeholder="{{trans('front.paginas.checkout.datosEnvio.cp')}}*" maxlength="9">
                                        <label for="zipcodeInput">{{trans('front.paginas.checkout.datosEnvio.cp')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.direcciones.itemSeleccionado.info_adicional" maxlength="35"  placeholder="{{trans('front.paginas.checkout.datosEnvio.infoAdicional')}}">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosEnvio.infoAdicional')}}</label>
                                    </div>

                                    <button type="button" class="btn btn-form f-right" @click="cerrarDireccion()">{{trans('front.paginas.checkout.btnCancelar')}}</button>
                                    <button type="button" class="btn btn-form f-right" style="margin-right: 10px;" @click="guardarDireccion()">{{trans('front.paginas.checkout.btnGuardar')}}</button>


                                </form>
                            </div>

                            <!-- END DESTINATARIO -->



                    <!-- END DESTINATARIO -->
                </template>

                <template v-if="checkout.secciones.datosDestinatario">
                            <!-- DESTINATARIO -->

                            <div class="block-content">

                                <h2>{{trans('front.paginas.checkout.datosDestinatario.titulo')}}</h2>
                                <form>
                                    <fieldset :disabled="checkout.seccionActual !== 'datosDestinatario'">
                                        <div class="form-floating  mb-form">
                                            <input type="text" class="form-control" id="nombreInput" placeholder=">Nombre*" v-model="checkout.form.nombre">
                                            <label for="nombreInput">{{trans('front.paginas.checkout.datosDestinatario.form.nombre')}}*</label>
                                        </div>

                                        <div class="form-floating  mb-form">
                                            <input type="text" class="form-control" id="apellidoInput" placeholder=">Apellido*" v-model="checkout.form.apellido">
                                            <label for="apellidoInput">{{trans('front.paginas.checkout.datosDestinatario.form.apellido')}}*</label>
                                        </div>

                                        <div class="form-floating  mb-form">
                                            <input type="text" class="form-control" id="dniInput" placeholder=">Dni*" v-model="checkout.form.dni">
                                            <label for="dniInput">{{trans('front.paginas.checkout.datosDestinatario.form.dni')}}*</label>
                                        </div>

                                        <button type="button" class="btn btn-primary f-right" @click="confirmarSeccion('datosDestinatario')" v-show="checkout.seccionActual === 'datosDestinatario'">{{trans('front.paginas.checkout.btnConfirmar')}}</button>
                                    </fieldset>
                                    <button type="button" class="btn btn-primary f-right" @click="editarSeccion('datosDestinatario')" v-show="checkout.seccionActual === 'datosFacturacion'">{{trans('front.paginas.checkout.btnEditar')}}</button>

                                </form>
                            </div>
                </template>
				<!-- DATOS DE FACTURACIÓN -->
				<template v-if="checkout.secciones.datosFacturacion">
                    <div class="block-content" id="seccionFacturacion">
                        <div class="titulo">
                            <h2>{{trans('front.paginas.checkout.datosFacturacion.titulo')}}</h2>
                            <div class="content-check">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" v-model="checkout.form.tipo_factura" :value="'CF'" @change="seleccionarTipoFactura">
                                  <label class="form-check-label" for="exampleRadios1">
                                   {{trans('front.paginas.checkout.datosFacturacion.form.tipoFacturaCF')}}
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" v-model="checkout.form.tipo_factura" :value="'A'" @change="seleccionarTipoFactura">
                                  <label class="form-check-label" for="exampleRadios2">
                                   {{trans('front.paginas.checkout.datosFacturacion.form.tipoFacturaA')}}
                                  </label>
                                </div>
                            </div>

                            <!--div class="dropdown">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span v-if="!checkout.form.tipo_factura">{{trans('front.paginas.checkout.datosFacturacion.titulo')}}</span>
                                    <span v-else>(% checkout.form.tipo_factura_desc %)</span>
                                    <i class="arrow-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" href="javascript:void(0);" @click="seleccionarTipoFactura('CF')">{{trans('front.paginas.checkout.datosFacturacion.form.tipoFacturaCF')}}</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" @click="seleccionarTipoFactura('A')">{{trans('front.paginas.checkout.datosFacturacion.form.tipoFacturaA')}}</a></li>
                                </ul>
                            </div-->
                        </div>

                        <form v-if="checkout.form.tipo_factura">
                            <fieldset :disabled="checkout.seccionActual !== 'datosFacturacion'">
                                <!-- Email input -->
                                <template v-if="checkout.form.tipo_factura == 'CF'">
                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" id="nombreInput" placeholder=">Nombre*" v-model="checkout.form.nombre_fc">
                                        <label for="nombreInput">{{trans('front.paginas.checkout.datosDestinatario.form.nombre')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" id="apellidoInput" placeholder=">Apellido*" v-model="checkout.form.apellido_fc">
                                        <label for="apellidoInput">{{trans('front.paginas.checkout.datosDestinatario.form.apellido')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" id="dniInput" placeholder=">Dni*" v-model="checkout.form.dni_fc">
                                        <label for="dniInput">{{trans('front.paginas.checkout.datosDestinatario.form.dni')}}*</label>
                                    </div>

                                    <div class="form-check " style="margin-bottom: 10px;">
                                        <label class="container">{{trans('front.paginas.checkout.datosFacturacion.form.usarDatosDest')}}
                                            <input type="checkbox" checked="checked" name="terminos-condiciones" v-model="checkout.form.usarDatosDest" @change="alCambiarUsarDatosDest">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" id="razonSocialInput" placeholder=">Razón social*" v-model="checkout.form.razon_social">
                                        <label for="razonSocialInput">{{trans('front.paginas.checkout.datosFacturacion.form.razon_social')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" id="cuitInput" placeholder=">Cuit*" v-model="checkout.form.cuit">
                                        <label for="cuitInput">{{trans('front.paginas.checkout.datosFacturacion.form.cuit')}}*</label>
                                    </div>


                                </template>
                                <template v-if="checkout.form.tipo_factura == 'A' || checkout.form.total >= checkout.info.montoDatosFC">
                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control" v-model="checkout.form.direccion_fc" placeholder="Calle y número*">
                                        <label for="calleInput">{{trans('front.paginas.checkout.datosFacturacion.form.direccion')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.form.ciudad_fc" placeholder=">Ciudad*">
                                        <label for="ciudadInput">{{trans('front.paginas.checkout.datosFacturacion.form.ciudad')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.form.provincia_fc" placeholder=">Provincia*">
                                        <label for="provinciaInput">{{trans('front.paginas.checkout.datosFacturacion.form.provincia')}}*</label>
                                    </div>

                                    <div class="form-floating  mb-form">
                                        <input type="text" class="form-control"  v-model="checkout.form.cp_fc" placeholder=">Código Postal*">
                                        <label for="zipcodeInput">{{trans('front.paginas.checkout.datosFacturacion.form.cp')}}*</label>
                                    </div>

                                    <p class="message">En caso de necesitar Factura A la percepción de Ingresos Brutos no está incluida en el precio.</p>
                                </template>
                                <button type="button" class="btn btn-primary f-right" @click="confirmarSeccion('datosFacturacion')" v-show="checkout.seccionActual === 'datosFacturacion'">{{trans('front.paginas.checkout.btnConfirmar')}}</button>
                            </fieldset>
                            <button type="button" class="btn btn-primary f-right" @click="editarSeccion('datosFacturacion')" v-show="checkout.seccionActual === 'confirmarPedido'">{{trans('front.paginas.checkout.btnEditar')}}</button>

                        </form>
                    </div>
                </template>
				<!-- END DATOS DE FACTURACIÓN -->

                <!-- COMENTATARIOS -->
                <div class="block-content" v-if="checkout.secciones.confirmarPedido">

                                <h2>{{trans('front.paginas.checkout.comentarios.titulo')}}</h2>
                                <form>
                                    <!-- Email input -->
                                    <fieldset :disabled="checkout.seccionActual !== 'confirmarPedido'">


                                    <div class="form-floating  mb-form">
                                          <textarea class="form-control" placeholder="Leave a comment here" id="comentariosInput" style="height: 100px" v-model="checkout.form.comentarios"></textarea>
                                            <label for="comentariosInput">{{trans('front.paginas.checkout.comentarios.titulo')}}</label>
                                    </div>


                                    <button type="button" class="btn btn-primary f-right" @click="confirmarSeccion('confirmarPedido')" v-show="checkout.seccionActual === 'confirmarPedido'">{{trans('front.paginas.checkout.btnConfirmar')}}</button>
                                    </fieldset>
                                </form>
                            </div>
                <!-- END COMENTATARIOS -->
			</div>

			<div class="col-md-4 col-checkout-2">
				<div class="checkout-detail">

					<div class="info">

						<table class="table table-borderless list">
							<tbody>
                                <template v-for="item in carrito.items">
                                    <tr>
                                        <td colspan="2">
                                            <img class="image-product" :src="item.attributes.imagen" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="display: block;"> (% item.name %) X (% item.quantity %)</span>
                                            <span class="price">(% (item.quantity * item.price) | currency %)</span>
                                        </td>
                                    </tr>
                                </template>
							</tbody>
						</table>


						<table class="table table-borderless totals">
							<tbody>
								<tr>
									<td class="subtotal">{{trans('front.paginas.checkout.cantidad')}}</td>
									<td class="subtotal"><span class="price">(% carrito.total | currency %)</span></td>
								</tr>
								<tr>
									<td class="envio">{{trans('front.paginas.checkout.costoEnvio')}}</td>
									<td class="envio">
                                        <span v-show="!checkout.cotizando_envio" class="price">(% locale == 'es' ? checkout.form.total_envio : checkout.form.total_envio | currency %)</span>
                                        <span v-show="checkout.cotizando_envio" class="price">--</span>
                                    </td>

								</tr>
								<tr>
									<td><b>{{trans('front.paginas.checkout.total')}}</b></td>
									<td><span class="price"><b>(% locale == 'es' ? checkout.form.total : checkout.form.total | currency %)</b></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
                <span class="mt-2" style="font-size: 10px;display: inline-block;text-align:justify;">{!!trans('front.paginas.checkout.envioSoloArgentina')!!}</span>
			</div>

		</div>
	</div>

</section>


@endsection
