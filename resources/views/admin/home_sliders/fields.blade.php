<template  v-if="!selectedItem.lang">
    <!-- ImagenMobile Field -->
    <div class="form-group col-sm-4" :class="{'has-error': errors.has('imagen_mobile')}">
        {!! Form::label('imagen_mobile', 'Imagen Mobile') !!}
        <div class="thumb-wrap">
            <button-type v-if="selectedItem.imagen_mobile" type="remove-list" @click="removeFile('imagen_mobile')"></button-type>
            <file-upload
                
                :multiple="false"
                :headers="_fuHeader"
                ref="uploadImagenMobile"
                extensions="gif,jpg,jpeg,png,webp,svg"
                accept="image/png,image/gif,image/jpeg,image/webp,image/svg"            
                input-id="imagen_mobile"
                v-model="files.imagen_mobile"
                post-action="{{ route('uploads.store-file') }}"
                @input-file="inputImagenMobile"
                class="thumbnail">
                    <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.imagen_mobile">
                    <img class="img-responsive" :src="selectedItem.imagen_mobile_url" v-else>
                    <div class="progress m-t-5 m-b-0" v-if="files.imagen_mobile.length > 0">
                        <div class="progress-bar" :style="{ width: files.imagen_mobile[0].progress+'%' }"></div>
                    </div>
            </file-upload>
        </div>     
        <span class="help-block" v-show="errors.has('imagen_mobile')">(% errors.first('imagen_mobile') %)</span>
    </div>
    <!-- ImagenWeb Field -->
    <div class="form-group col-sm-4" :class="{'has-error': errors.has('imagen_desktop')}">
        {!! Form::label('imagen_desktop', 'Imagen Desktop') !!}
        <div class="thumb-wrap">
            <button-type v-if="selectedItem.imagen_desktop" type="remove-list" @click="removeFile('imagen_desktop')"></button-type>
            <file-upload
                
                :multiple="false"
                :headers="_fuHeader"
                ref="uploadImagenDesktop"
                extensions="gif,jpg,jpeg,png,webp,svg"
                accept="image/png,image/gif,image/jpeg,image/webp,image/svg"            
                input-id="imagen_desktop"
                v-model="files.imagen_desktop"
                post-action="{{ route('uploads.store-file') }}"
                @input-file="inputImagenDesktop"
                class="thumbnail">
                    <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.imagen_desktop">
                    <img class="img-responsive" :src="selectedItem.imagen_desktop_url" v-else>
                    <div class="progress m-t-5 m-b-0" v-if="files.imagen_desktop.length > 0">
                        <div class="progress-bar" :style="{ width: files.imagen_desktop[0].progress+'%' }"></div>
                    </div>
            </file-upload>
        </div>     
        <span class="help-block" v-show="errors.has('imagen_desktop')">(% errors.first('imagen_desktop') %)</span>
    </div>
    <!-- Video Field -->
    <div class="form-group col-sm-4" :class="{'has-error': errors.has('video')}">
        {!! Form::label('video', 'Video') !!}
        <div class="">
            <button-type v-if="selectedItem.video" type="remove-list" @click="removeFile('video')"></button-type>
            <file-upload
                
                :multiple="false"
                :headers="_fuHeader"
                ref="uploadVideo"
                input-id="video"
                v-model="files.video"
                post-action="{{ route('uploads.store-file') }}"
                @input-file="inputVideo"
                class="">
                    <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.video" style="max-width: 100px;">
                    <div style="width: 100%; text-align: left;"  v-else>
                        <i class="fa fa-file" style="font-size: 25px; margin-right: 5px;"> </i>
                       <span>(% selectedItem.video_url %)</span>
                    </div>
                    <div class="progress m-t-5 m-b-0" v-if="files.video.length > 0">
                        <div class="progress-bar" :style="{ width: files.video[0].progress+'%' }"></div>
                    </div>
            </file-upload>
        </div>   
        <span class="help-block" v-show="errors.has('video')">(% errors.first('video') %)</span>
    </div>
</template>


<!-- Titulo Field -->
<div class="form-group col-sm-12" :class="{'has-error': errors.has('titulo')}">
    {!! Form::label('titulo', 'Titulo') !!}
    {!! Form::text('titulo', null, ['class' => 'form-control','v-model' => 'selectedItem.titulo']) !!}
    <span class="help-block" v-show="errors.has('titulo')">(% errors.first('titulo') %)</span>
</div>

<!-- Subtitulo Field -->
<div class="form-group col-sm-12" :class="{'has-error': errors.has('subtitulo')}">
    {!! Form::label('subtitulo', 'Subtitulo') !!}
    {!! Form::text('subtitulo', null, ['class' => 'form-control','v-model' => 'selectedItem.subtitulo']) !!}
    <span class="help-block" v-show="errors.has('subtitulo')">(% errors.first('subtitulo') %)</span>
</div>

<!-- Boton Titulo Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('boton_titulo')}">
    {!! Form::label('boton_titulo', 'Boton Titulo') !!}
    {!! Form::text('boton_titulo', null, ['class' => 'form-control','v-model' => 'selectedItem.boton_titulo']) !!}
    <span class="help-block" v-show="errors.has('boton_titulo')">(% errors.first('boton_titulo') %)</span>
</div>

<!-- Boton Url Field -->
<div class="form-group col-sm-8" :class="{'has-error': errors.has('boton_url')}">
    {!! Form::label('boton_url', 'Boton Url') !!}
    {!! Form::text('boton_url', null, ['class' => 'form-control','v-model' => 'selectedItem.boton_url']) !!}
    <span class="help-block" v-show="errors.has('boton_url')">(% errors.first('boton_url') %)</span>
</div>

<!-- Orden Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('orden')}" v-if="!selectedItem.lang">
    {!! Form::label('orden', 'Orden') !!}
    {!! Form::number('orden', null, ['class' => 'form-control','v-model' => 'selectedItem.orden']) !!}
    <span class="help-block" v-show="errors.has('orden')">(% errors.first('orden') %)</span>
</div>

<div class="form-group col-sm-4" :class="{'has-error': errors.has('enabled')}" v-if="!selectedItem.lang">
    {!! Form::label('enabled', 'Visible') !!}<br>
    <switch-button v-model="selectedItem.enabled" theme="bootstrap" type-bold="true"></switch-button>
    <span class="help-block" v-show="errors.has('enabled')">(% errors.first('enabled') %)</span>
</div>
<div class="clearfix"></div>