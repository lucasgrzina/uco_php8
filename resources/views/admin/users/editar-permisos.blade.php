@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/cu.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>

    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
    </script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Usuarios','Editar permisos',false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::label('perm', 'Permisos') !!}
                        <div class="table-responsive no-padding">
                            <table class="table table-condensed">
                                <tbody>
                                    <tr>
                                      <th style="border-top:none;border-bottom: 1px solid #f4f4f4;"></th>
                                      <th class="text-center" style="width: 80px;border-top:none;border-bottom: 1px solid #f4f4f4;background-color: #f4f4f4;">Editar</th>
                                      <th class="text-center" style="width: 80px;border-top:none;border-bottom: 1px solid #f4f4f4;background-color: #f4f4f4;">Ver</th>
                                    </tr>
                                    <tr v-for="(item,key) in info.permisos">
                                      <td>(% key %)</td>
                                      <td v-for="perm in item" class="text-center" style="background-color: #fff;">
                                        <input type="checkbox" :value="perm" v-model="selectedItem.permissions" :disabled="selectedItem.permisos_rol.indexOf(perm) > -1">
                                      </td>
                                    </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>            
        </div>    
    </div>
@endsection