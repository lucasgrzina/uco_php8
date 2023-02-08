@extends('layouts.front')
@section('css')
    @parent
    <style>
        .text-right {
            text-align: right!important;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        _methods.verPedido = function(item) {
            window.location = this.pedidos.url_detalle.replace('_ID_',item.id);
        };

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
                            <th scope="col">{{trans('front.paginas.miCuenta.pedidos.tabla.fecha')}}</th>
                            <th scope="col">{{trans('front.paginas.miCuenta.pedidos.tabla.estado')}}</th>
                            <th scope="col" class=" text-right">{{trans('front.paginas.miCuenta.pedidos.tabla.subtotal')}}</th>
                            <th scope="col" class=" text-right">{{trans('front.paginas.miCuenta.pedidos.tabla.envio')}}</th>
                            <th scope="col" class=" text-right">{{trans('front.paginas.miCuenta.pedidos.tabla.total')}}</th>
                        </tr>
                    </thead>
                    <tbody v-for="(item,index) in pedidos.listado">
                        <tr>
                            <th scope="row" colspan="2" style="position: relative; width: 30px;">
                                <a href="javascript:void(0)" class="" @click="verPedido(item)">{{trans('front.paginas.miCuenta.pedidos.lnkVer')}}</a>
                            </th>
                            <td class="align-middle">
                                <span>(% item.created_at | datetimeFormat %)</span>
                            </td>
                            <td class="align-middle"><span>(% item.estado %)</span></td>
                            <td class="align-middle text-right"><span>(% locale == 'es' ? item.total_carrito : item.total_carrito | currency %)</span></td>
                            <td class="align-middle text-right"><span>(% locale == 'es' ? item.total_envio : item.total_envio | currency %)</span></td>
                            <td class="align-middle text-right"><span>(% locale == 'es' ? item.total : item.total | currency %)</span></td>

                        </tr>

                    </tbody>
                </table>
            </div>
            <!--div class="col-12">
                <button class="btn btn-primary btn-finalizar">Finalizar compra</button>
            </div-->
        </div>
    </div>
</section>


@endsection
