@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/cu.css') }}"/>
@endsection

@section('scripts')
    @parent
    <!--script type="text/javascript" src="{{ asset('vendor/vue-upload-component/vue-upload-component.min.js') }}"></script-->
    <script type="text/javascript">
        //Vue.component('file-upload', VueUploadComponent);
        var _data = {!! json_encode($data) !!};
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Pedido',isset($data['selectedItem']->id) && $data['selectedItem']->id > 0 ? trans('admin.edit') : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.pedidos.fields')
                </div>
                <div class="table-responsive">
                    <table class="table m-b-0" id="pedidos-table">
                        <thead>
                            <tr>
                                <th>Vino/Añada</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio Unitario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in selectedItem.items">
                                <td>(% item.aniada && item.aniada.vino ? item.aniada.vino.titulo : '--' %) ((% item.aniada ? item.aniada.anio : '' %))</td>
                                <td class="text-center">
                                    (% item.cantidad %)
                                </td>
                                <td class="text-right">(% item.precio_pesos | currency %)</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Subtotal</th>
                                <th class="text-right">(% selectedItem.total_carrito | currency %)</th>
                            </tr>
                            <tr>
                                <th colspan="2">Costo de envío</th>
                                <th class="text-right">(% selectedItem.total_envio | currency %)</th>
                            </tr>
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-right">(% selectedItem.total | currency %)</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>
        </div>
    </div>
@endsection
