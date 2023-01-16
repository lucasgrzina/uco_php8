@extends('layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/cu.css') }}"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ asset('vendor/vue-upload-component/vue-upload-component.min.js') }}"></script>
    <script type="text/javascript">
        Vue.component('file-upload', VueUploadComponent);
        var _data = {!! json_encode($data) !!};
        _data.files = {
            imagen_desktop: [],
            imagen_mobile: [],
            video: []
        };          

        _methods.inputImagenDesktop = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
                console.debug(file);
              //_this.errors.remove('files');
              _this.selectedItem.imagen_desktop_url = file.response.path;
              _this.selectedItem.imagen_desktop = file.response.file;          
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImagenDesktop');
        }         

        _methods.inputImagenMobile = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
              _this.selectedItem.imagen_mobile_url = file.response.path;
              _this.selectedItem.imagen_mobile = file.response.file;          
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImagenMobile');
        }     
        
        _methods.inputVideo = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
            _this.selectedItem.video_url     = file.response.path;      
            _this.selectedItem.video         = file.response.file;   

          }, function(file) {
            //_this.errors.add('light_cv',file.error, 'server');
          },'uploadVideo');
        }           
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Home Slider',isset($data['selectedItem']->id) && $data['selectedItem']->id > 0 ? trans('admin.edit') : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.home_sliders.fields')
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>            
        </div>    
    </div>
@endsection