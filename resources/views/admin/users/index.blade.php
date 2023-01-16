@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/index.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
        
        _methods.editarPermisos = function(item) {
            document.location = this.url_editar_permisos.replace('_ID_',item.id);
        };

        this._mounted.push(function(_this) {
            _this.getStoredFilters();
            _this.doFilter();
        });
    </script>
    <script src="{{ asset('vendor/vuejs-paginate/vuejs-paginate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/index.js') }}"></script>
@endsection

@section('content-header')
{!! AdminHelper::contentHeader('Listado de usuarios','Listado de usuarios. El buscador solo busca por nombre y apellido.',auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']) ? 'new' : '','create()') !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-page-list">
            <div class="box-body box-filter">
                <div class="form-inline">
                    @include('admin.includes.crud.index-filters-input')
                    <div class="form-group">
                        <select v-model="filters.rol" class="form-control input-sm" name="rol">
                            <option v-for="item in info.roles" :value="item.id">(% item.name %)</option>
                            <option :value="null">Roles (todos)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select v-model="filters.enabled" class="form-control input-sm" name="enabled" >
                            <option :value="1">Usuarios activos</option>
                            <option :value="0">Usuarios inactivos</option>
                            <option :value="null">Todos</option>
                        </select>  
                    </div>
                    @include('admin.includes.crud.index-filters-btn')
                </div>
            </div>
            <div class="box-body box-list no-padding">
                    @include('admin.users.table')
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