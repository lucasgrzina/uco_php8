@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/index.css') }}"/>
    <style>
        .label-gris {
            background: #d9d7d7;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        _methods.cambiarEstatus = function (item, campo) {
            var _this = this;
            var _data = {
                accion: "cambiarValor"
            };
            _this.loading = true;
            _data[campo] = !item[campo];
            _this.ajaxPut(_this.url_save.replace('_ID_',item.id), _data).then(function(data) {
                _this.doFilter();
            }, function(error) {
                _this.loading = false;
            });
        };

        this._mounted.push(function(_this) {
            _this.doFilter();
        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vuejs-paginate/vuejs-paginate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/index.js') }}"></script>
@endsection

@section('content-header')
{!! AdminHelper::contentHeader('Contactos',trans('admin.list')) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-page-list">
            <div class="box-body box-filter">
                <div class="form-inline">
                    @include('admin.includes.crud.index-filters-input')
                    <!-- cualquier otro campo -->
                    <div class="form-group">
                        <select v-model="filters.recibir_info" class="form-control input-sm" name="enabled" >
                            <option :value="1">Si</option>
                            <option :value="0">No</option>
                            <option :value="null">Todos</option>
                        </select>
                    </div>
                    @include('admin.includes.crud.index-filters-btn')
                </div>
            </div>
            <div class="box-body box-list no-padding">
                    @include('admin.configuraciones.table')
            </div>
            <div class="box-footer">
                <div class="col-sm-8 left">
                    <span v-if="!loading">(% paging.total %) registro(s)</span>
                </div>
                <div class="col-sm-4 right">
                    @include('admin.includes.crud.index-pagination')
                </div>
            </div>
            @include('admin.includes.crud.index-loading')
        </div>
    </div>
@endsection

