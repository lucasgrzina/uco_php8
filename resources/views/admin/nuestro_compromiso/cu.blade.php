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
            imagen_home: [],
            imagen_interna: [],
        };

        _methods.inputImagenHome = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
                console.debug(file);
              //_this.errors.remove('files');
              _this.selectedItem.imagen_home_url = file.response.path;
              _this.selectedItem.imagen_home = file.response.file;
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImagenHome');
        }
        _methods.inputImagenInterna = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
                console.debug(file);
              //_this.errors.remove('files');
              _this.selectedItem.imagen_interna_url = file.response.path;
              _this.selectedItem.imagen_interna = file.response.file;
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImagenInterna');
        }
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Nuestro compromiso',isset($data['selectedItem']->id) && $data['selectedItem']->id > 0 ? $data['selectedItem']->nombre : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.nuestro_compromiso.fields')
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>
        </div>
    </div>
@endsection
