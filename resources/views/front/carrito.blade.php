@extends('layouts.front')

@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        _methods.cambiarCantidad = function (item, nro) {
            var _this = this;
            console.debug(item);
            var cantidad = item.quantity + nro;
            console.debug(cantidad);
            if (cantidad >= 0 && cantidad <= _this.cantMaxima(item)) {
                Vue.set(item,'quantity',cantidad);
                _this.carritoModificarItem(item,cantidad)
            }
        //$('#span-cantidad').html(cantidad);
        }


        _methods.cantMaxima = function(item) {
            return 18;
        }

        _methods.checkCantidad = function(item) {
            /*console.debug('checkCantidad');
            var cantMax = this.cantMaxima(item);
            console.debug([item.quantity,cantMax]);
            if (item.quantity > cantMax) {
                Vue.set(item,'quantity',parseInt(cantMax));
                _this.carritoModificarItem(item,item.quantity);
                //this.carrito.item.cantidad = cantMax;
            }
            if (item.quantity == '' || parseInt(item.quantity) < 1){
                Vue.set(item,'quantity',1);
                _this.carritoModificarItem(item,item.quantity);
                //this.carrito.item.cantidad = 0;
            }*/

        }

        this._mounted.push(function(_this) {
        });
    </script>
@endsection
@php
    //$actual = $data['actual'];
@endphp
@section('content')
<section class="section-table">
    <div class="container">
        <div class="row">
            <div class="col-12 table-responsive-custom">
                <table class="table table-borderless table-light">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2"></th>
                            <th scope="col">{{trans('front.paginas.carrito.producto')}}</th>
                            <th scope="col" style="text-align: right;">{{trans('front.paginas.carrito.precio')}}</th>
                            <th scope="col" style="text-align: right;">{{trans('front.paginas.carrito.cantidad')}}</th>
                            <th scope="col" style="text-align: right;">{{trans('front.paginas.carrito.subtotal')}}</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody v-for="(item,key,index) in carrito.items">
                        <tr>
                            <th scope="row" colspan="2" style="position: relative; width: 30px;">
                                <!--<a href="javascript:void(0)" class="remove" @click="carritoQuitarItem(item,index)"></a>-->
                            </th>
                            <td>
                                <div class="item">
                                    <div class="image"><img :src="item.attributes.imagen" /></div>
                                    <div class="info">
                                        <h1>(% item.attributes.nombre_vino %)</h1>
                                        <span>(% item.attributes.aniada %)</span>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle" style="text-align: right;"><span>(% item.price | currency %)</span></td>
                            <td class="align-middle" style="text-align: right;">
                                <div class="input-cantidad cart-input">
                                    <button class="btn-cantidad plus" @click="cambiarCantidad(item,-1)">-</button>
                                    <input type="number" placeholder="1" readonly :min="1" :max="cantMaxima(item)" v-model="item.quantity" @blur="checkCantidad(item)">
                                    <button class="btn-cantidad minus" @click="cambiarCantidad(item,1)">+</button>
                                </div>

                                <!--div class="dropdown">
                                    <button class="btn btn-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span>(% item.quantity %)</span>
                                        <i class="arrow-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li v-for="cant in 20"><a class="dropdown-item" href="javascript:void(0)" @click="carritoModificarItem(item,cant)">(% cant %)</a></li>
                                    </ul>
                                </div-->
                            </td>
                            <td class="align-middle" style="text-align: right;"><span>(%(item.quantity * item.price) | currency%)</span></td>
                            <td class="align-middle" style="text-align: right;"><a href="javascript:void(0)" style="color: #000;  position: relative; display: block;" @click="carritoQuitarItem(item,index)">{{trans('front.paginas.carrito.eliminar')}}</a></td>
                        </tr>
                        <!--tr>
                            <td colspan="5"></td>
                            <td class="align-middle"><a href="javascript:void(0)" style="color: #000; margin: -44px 0 0;  position: relative; display: block;" @click="carritoQuitarItem(item,index)">Eliminar</a></td>
                        </tr-->

                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col" colspan="4">&nbsp;</th>
                            <th scope="col" style="text-align: right;font-weight:600;">{{trans('front.paginas.carrito.subtotal')}}</th>
                            <th scope="col" style="text-align: right;font-weight:600;">(%carrito.total | currency%)</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-12">
                <button v-if="carrito.cantidad > 0" class="btn btn-primary btn-finalizar" @click="goTo('{{routeIdioma('checkout')}}')">{{trans('front.paginas.carrito.finalizar')}}</button>
                @if (auth()->guest())
                <button class="btn btn-primary btn-finalizar" style="float: right;margin-right:5px;" v-if="carrito.cantidad > 0" href="{{routeIdioma('checkout.anonimo')}}" @click="goTo('{{routeIdioma('checkout.anonimo')}}')">{{trans('front.paginas.carrito.contSinRegistrarse')}}</button>
                @endif
            </div>
            <div class="col-12">
                <div class="shop">
                    <p class="destacado mt-3 text-center">
                        {!! str_replace('_COMPRAS_SUPERIORES_',$data['configuraciones']['COMPRAS_SUPERIORES'],trans('front.paginas.colecciones.interna.porCompras')) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
