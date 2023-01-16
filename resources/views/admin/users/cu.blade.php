@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/cu.css') }}"/>
@endsection

@section('scripts')
    @parent
    <!--script src="https://unpkg.com/vuejs-datepicker"></script-->
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>

    <script type="text/javascript">
        //Vue.component('datepicker', vuejsDatepicker);
        var _data = {!! json_encode($data) !!};

        _methods.esRolVendedor = function () {
            var _this = this;
            var _rol = _this.rolSeleccionado();

            return _rol && _rol.name.toLowerCase() === 'VENDEDORES'.toLowerCase();
        }

        _methods.rolSeleccionado = function () {
            var _this = this;
            var _rol = _.find(_this.info.roles,function(item) {
                return item.id == _this.selectedItem.role_id;
            });
            return _rol;
        }

        _methods.alCambiarRol = function () {
            var _this = this;
            if (!_this.esRolVendedor()) {
                _this.selectedItem.porc_comision = 0;
            }
        }
    </script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Usuarios',isset($data['selectedItem']['id']) && $data['selectedItem']['id'] > 0 ? trans('admin.edit') : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.users.fields')
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>            
        </div>    
    </div>
@endsection