@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/cu.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/tinymce/js/tinymce/vue-mce.web.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/vue-upload-component/vue-upload-component.min.js') }}"></script>
    <script type="text/javascript">
        Vue.component('file-upload', VueUploadComponent);
        var _data = {!! json_encode($data) !!};

        _data.files = {
            foto: []
        };

        _methods.inputFoto = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
                console.debug(file);
              //_this.errors.remove('files');
              _this.selectedItem.foto_url = file.response.path;
              _this.selectedItem.foto = file.response.file;
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadFoto');
        }
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Legados',isset($data['selectedItem']->id) && $data['selectedItem']->id > 0 ? trans('admin.edit') : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.legados.fields')
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>
        </div>
    </div>
@endsection
