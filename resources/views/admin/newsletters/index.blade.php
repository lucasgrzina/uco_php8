@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/index.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};

        this._mounted.push(function(_this) {
            _this.doFilter();
        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vuejs-paginate/vuejs-paginate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/index.js') }}"></script>
@endsection

@section('content-header')
{!! AdminHelper::contentHeader('Newsletters',trans('admin.list'),null,null) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-page-list">
            <div class="box-body box-filter">
                <div class="form-inline">
                    <!-- cualquier otro campo -->
                    <div class="form-group">
                        <label>Desde</label><br>
                        <input type="date" v-model="filters.fecha_desde" name="fecha_desde" class="form-control input-sm" :placeholder="'Fecha (desde)'">
                    </div>
                    <div class="form-group">
                        <label>Hasta</label><br>
                        <input type="date" v-model="filters.fecha_hasta" name="fecha_hasta" class="form-control input-sm" :placeholder="'Fecha (hasta)'">
                    </div>
                    <div class="form-group">
                        <label>Recibir Info</label><br>
                        <select v-model="filters.recibir_info" class="form-control input-sm" name="enabled" >
                            <option :value="1">Si</option>
                            <option :value="0">No</option>
                            <option :value="null">Todos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Busqueda general</label><br>
                        <input type="text" class="form-control input-sm" v-model="filters.search"  placeholder="{{ trans('admin.search') }}" @keyup.enter="filter">
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label><br>
                        <button-type type="filter" @click="filter()"></button-type>
                        <button-type v-if="filters.export_xls" type="export" @click="exportTo('xls')"></button-type>
                    </div>
                </div>
            </div>
            <div class="box-body box-list no-padding">
                    @include('admin.newsletters.table')
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

