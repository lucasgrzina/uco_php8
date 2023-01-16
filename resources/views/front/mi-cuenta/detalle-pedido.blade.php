@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};


        this._mounted.push(function(_this) {

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

				<div class="block-content">
					<h2>{{trans('front.paginas.miCuenta.detallePedido.datosContacto.titulo')}}</h2>
					<form>
						<!-- Email input -->
						<fieldset :disabled="true">
                            <div class="form-floating  mb-form">
                                <input type="email" class="form-control" id="emailInput" v-model="pedidos.pedido.email">
                                <label for="emailInput">{{trans('front.paginas.miCuenta.detallePedido.datosContacto.form.email')}}</label>
                            </div>
                        </fieldset>
					</form>
				</div>

                <div class="block-content" >
                    <h2>{{trans('front.paginas.miCuenta.detallePedido.envioRetiro.titulo')}}</h2>
                    <form>
                        <fieldset :disabled="true">
                            <div class="direccion">
                                <div class="info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <p><span>(% pedidos.pedido.direccion %)</span><span>(% pedidos.pedido.cp %),(% pedidos.pedido.ciudad %)</span><span>(% pedidos.pedido.nombre.concat(' ').concat(pedidos.pedido.apellido)%)</span></p>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <div class="block-content">

                    <h2>{{trans('front.paginas.miCuenta.detallePedido.datosDestinatario.titulo')}}</h2>
                    <form>
                        <fieldset :disabled="true">
                            <div class="form-floating  mb-form">
                                <input type="text" class="form-control" id="nombreInput" placeholder=">Nombre*" v-model="pedidos.pedido.nombre">
                                <label for="nombreInput">{{trans('front.paginas.miCuenta.detallePedido.datosDestinatario.form.nombre')}}</label>
                            </div>

                            <div class="form-floating  mb-form">
                                <input type="text" class="form-control" id="apellidoInput" placeholder=">Apellido*" v-model="pedidos.pedido.apellido">
                                <label for="apellidoInput">{{trans('front.paginas.miCuenta.detallePedido.datosDestinatario.form.apellido')}}</label>
                            </div>

                            <div class="form-floating  mb-form">
                                <input type="text" class="form-control" id="dniInput" placeholder=">Dni*" v-model="pedidos.pedido.dni">
                                <label for="dniInput">{{trans('front.paginas.miCuenta.detallePedido.datosDestinatario.form.dni')}}</label>
                            </div>
                        </fieldset>

                    </form>
                </div>

                <div class="block-content">
                    <h2>{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.titulo')}}</h2>
                    <form>
                        <fieldset :disabled="true">
                            <div class="form-floating  mb-form">
                                <input v-if="pedidos.pedido.tipo_factura == 'CF'" type="text" class="form-control" id="tipoFacturaInput" value="{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.tipoFacturaCF')}}">
                                <input v-if="pedidos.pedido.tipo_factura == 'A'" type="text" class="form-control" id="tipoFacturaInput" value="{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.tipoFacturaA')}}">
                                <label for="tipoFacturaInput">{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.tipoFactura')}}</label>
                            </div>
                            <template v-if="pedidos.pedido.tipo_factura == 'A'">
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" id="tipoFacturaRzInput" v-model="pedidos.pedido.razon_social">
                                    <label for="tipoFacturaRzInput">{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.razon_social')}}</label>
                                </div>
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" id="tipoFacturaCuitInput" v-model="pedidos.pedido.cuit">
                                    <label for="tipoFacturaCuitInput">{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.cuit')}}</label>
                                </div>
                            </template>
                            <template v-if="pedidos.pedido.direccion_fc">
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" v-model="pedidos.pedido.direccion_fc">
                                    <label >{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.direccion')}}</label>
                                </div>
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" v-model="pedidos.pedido.ciudad_fc">
                                    <label >{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.ciudad')}}</label>
                                </div>
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" v-model="pedidos.pedido.provincia_fc">
                                    <label >{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.provincia')}}</label>
                                </div>
                                <div class="form-floating  mb-form">
                                    <input type="text" class="form-control" v-model="pedidos.pedido.cp_fc">
                                    <label >{{trans('front.paginas.miCuenta.detallePedido.datosFacturacion.form.cp')}}</label>
                                </div>
                            </template>
                        </fieldset>
                    </form>
                </div>

                <div class="block-content">

                    <h2>{{trans('front.paginas.miCuenta.detallePedido.comentarios.titulo')}}</h2>
                    <form>
                        <!-- Email input -->
                        <fieldset :disabled="true">


                        <div class="form-floating  mb-form">
                              <textarea class="form-control"  id="comentariosInput" style="height: 100px" v-model="pedidos.pedido.comentarios"></textarea>
                            <label for="comentariosInput"></label>
                        </div>
                        </fieldset>
                        <button type="button" class="btn btn-primary f-right" @click="goTo(pedidos.url_listado)">{{trans('front.paginas.miCuenta.detallePedido.btnVolver')}}</button>
                    </form>
                </div>

			</div>

			<div class="col-md-4 col-checkout-2">
				<div class="checkout-detail">

					<div class="info">

						<table class="table table-borderless list">
							<tbody>
                                <template v-for="item in pedidos.pedido.items">
                                    <tr>
                                        <td colspan="2">
                                            <img class="image-product" :src="item.aniada.vino.imagen_url" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(% item.aniada.vino.titulo + ' - ' + item.aniada.anio %) X (% item.cantidad %)</td>

                                        <td><span class="price">(% locale == 'es' ? item.precio_pesos : item.precio_usd | currency %)</span></td>
                                    </tr>
                                </template>
							</tbody>
						</table>


						<table class="table table-borderless totals">
							<tbody>
								<tr>
									<td>Subtotal</td>
									<td><span class="price">(% locale == 'es' ? pedidos.pedido.total_carrito : pedidos.pedido.total_carrito_usd | currency %)</span></td>
								</tr>
								<tr>
									<td>Costo de envío</td>
									<td>
                                        <span class="price">(% locale == 'es' ? pedidos.pedido.total_envio : pedidos.pedido.total_envio_usd | currency %)</span>
                                    </td>

								</tr>
								<tr>
									<td><b>Total</b></td>
									<td><span class="price"><b>(% locale == 'es' ? pedidos.pedido.total : pedidos.pedido.total_usd | currency %)</b></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>

</section>


@endsection
