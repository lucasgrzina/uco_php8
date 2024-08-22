@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/index.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        _methods.listadoHijos = function(item) {
            this.storeFilters();
            document.location = this.url_hijos.replace('_ID_',item.id);
        };

        this._mounted.push(function(_this) {
            _this.doFilter();
        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vuejs-paginate/vuejs-paginate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/index.js') }}"></script>
@endsection

@section('content-header')
{!! AdminHelper::contentHeader('Vinos',trans('admin.list'),'new','create()') !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-page-list">
            <div class="box-body box-filter">
                <div class="form-inline">

                    <!-- cualquier otro campo -->
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" v-model="filters.titulo"  placeholder="titulo..." @keyup.enter="filter">
                    </div>
                    <div class="form-group">
                        <input style="max-width: 70px;" type="text" class="form-control input-sm" v-model="filters.peso"  placeholder="peso..." @keyup.enter="filter">
                        <input style="max-width: 70px;" type="text" class="form-control input-sm" v-model="filters.largo"  placeholder="largo..." @keyup.enter="filter">
                        <input style="max-width: 70px;" type="text" class="form-control input-sm" v-model="filters.ancho"  placeholder="ancho..." @keyup.enter="filter">
                        <input style="max-width: 70px;" type="text" class="form-control input-sm" v-model="filters.alto"  placeholder="alto..." @keyup.enter="filter">
                    </div>

                    @include('admin.includes.crud.index-filters-btn')
                </div>
            </div>
            <div class="box-body box-list no-padding">
                    @include('admin.vinos.table')
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

