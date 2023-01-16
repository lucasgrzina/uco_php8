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
@php
    $subtitle = isset($data['parent']) ? $data['parent']->titulo : trans('admin.list');
    $new = (auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']));
@endphp
{!! AdminHelper::contentHeader('Aniadas',$subtitle, ($new ? 'new' : false), ($new ? 'create()' : false)) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-page-list">
            <div class="box-body box-filter">
                <div class="form-inline">
                    @include('admin.includes.crud.index-filters-input')
                    <!-- cualquier otro campo -->
                    @include('admin.includes.crud.index-filters-btn')
                    <div class="form-group pull-left">
                        <button class="btn btn-sm btn-primary m-l-5" v-if="parent" @click="goToParent(parent)">
                            Volver
                        </button>               
                    </div>                      
                </div>
               
            </div>
            <div class="box-body box-list no-padding">
                    @include('admin.aniadas.table')
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

