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
            imagen: [],
            imagenes: [],
            formImage: {
                filename: null,
                filename_url: null,
                orden: 1,
                id: 0,
                delete: false          
            }            
        };          

        _data.newImage = {
            filename: null,
            filename_url: null,
            orden: 1,
            id: 0,
            delete: false          
        };        


        _methods.addImage = function () {
          if (!this.files.formImage.filename) {
            alert('Debe cargar una imagen.');
            return false;
          }
          
          this.selectedItem.imagenes.push(Vue.util.extend({}, this.files.formImage));
          this.files.formImage = Vue.util.extend({}, this.newImage);
        };  

        _methods.removeImage = function(item,index) 
        {
          item.delete = true;
        }

        _methods.inputImages = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
              //_this.errors.remove('files');
              _this.files.formImage.filename_url = file.response.path;
              _this.files.formImage.filename = file.response.file;
              console.debug(_this.files.formImage);           
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImage');
        }



        _methods.inputImagen = function (n,o) {
          var _this = this;
          this.inputFile(n,o,function(file) {
                console.debug(file);
              //_this.errors.remove('files');
              _this.selectedItem.imagen_url = file.response.path;
              _this.selectedItem.imagen = file.response.file;          
          }, function(file) {
            //_this.errors.add('light_logo',file.error, 'server');
          },'uploadImagen');
        }  
    </script>
    <script type="text/javascript" src="{{ asset('vendor/vee-validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/crud/js/cu.js') }}"></script>
@endsection

@section('content-header')
    {!! AdminHelper::contentHeader('Vinos',isset($data['selectedItem']->id) && $data['selectedItem']->id > 0 ? trans('admin.edit') : trans('admin.add_new'),false) !!}
@endsection

@section('content')
    @include('admin.components.switches')
    <div class="content">
        <div class="box box-default box-cu">
            <div class="box-body">
                <div class="row">
                        @include('admin.vinos.fields')
                </div>
            </div>
            <div class="box-footer text-right">
                <button-type type="save" :promise="store"></button-type>
                <button-type type="cancel" @click="cancel()"></button-type>
            </div>            
        </div>    
    </div>
@endsection