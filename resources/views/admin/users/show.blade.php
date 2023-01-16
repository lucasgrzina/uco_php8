@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/show.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ asset('admin/crud/js/show.js') }}"></script>
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
        _methods.back = function() {

        };
    </script>
@endsection
@section('content-header')
{!! AdminHelper::contentHeader('Usuarios',$data['selectedItem']['nombre_completo']) !!}
@endsection
@section('content')
    <div class="content">
        <div class="box box-default">
            <div class="box-body">
                @include('admin.users.show_fields')
            </div>
                        
            <div class="box-footer text-right">
                @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                    <button-type type="edit" @click="edit(selectedItem)"></button-type>
                @endif
                <button-type type="back" @click="goTo(url_index)"></button-type>
            </div>
            
        </div>
    </div>
@endsection
