<!-- Foto Field -->
<div class="form-group col-sm-2" :class="{'has-error': errors.has('foto')}" v-if="!selectedItem.lang">
    {!! Form::label('foto', 'Foto') !!}
    <div class="thumb-wrap">
        <button-type v-if="selectedItem.foto" type="remove-list" @click="removeFile('foto')"></button-type>
        <file-upload
            
            :multiple="false"
            :headers="_fuHeader"
            ref="uploadFoto"
            extensions="gif,jpg,jpeg,png,webp,svg"
            accept="image/png,image/gif,image/jpeg,image/webp,image/svg"            
            input-id="foto"
            v-model="files.foto"
            post-action="{{ route('uploads.store-file') }}"
            @input-file="inputFoto"
            class="thumbnail">
                <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.foto_url">
                <img class="img-responsive" :src="selectedItem.foto_url" v-else>
                <div class="progress m-t-5 m-b-0" v-if="files.foto.length > 0">
                    <div class="progress-bar" :style="{ width: files.foto[0].progress+'%' }"></div>
                </div>
        </file-upload>
    </div>     
    <span class="help-block" v-show="errors.has('foto')">(% errors.first('foto') %)</span>
</div>
<!-- Titulo Field -->
<div class="form-group col-sm-10" :class="{'has-error': errors.has('titulo')}">
    {!! Form::label('titulo', 'Titulo') !!}
    {!! Form::text('titulo', null, ['class' => 'form-control','v-model' => 'selectedItem.titulo']) !!}
    <span class="help-block" v-show="errors.has('titulo')">(% errors.first('titulo') %)</span>
</div>
<div class="clearfix"></div>
<div class="form-group col-sm-12" :class="{'has-error': errors.has('bajada')}">
    {!! Form::label('bajada', 'Bajada') !!}
    <textarea class="form-control" v-model="selectedItem.bajada"></textarea>
    <span class="help-block" v-show="errors.has('bajada')">(% errors.first('bajada') %)</span>
</div>
<!-- Cuerpo Field -->
<div class="form-group col-sm-12" :class="{'has-error': errors.has('cuerpo')}">
    {!! Form::label('cuerpo', 'Cuerpo') !!}
    <vue-mce v-model="selectedItem.cuerpo" :config="tinyConfig"/>
    <span class="help-block" v-show="errors.has('cuerpo')">(% errors.first('cuerpo') %)</span>
</div>
<template  v-if="!selectedItem.lang">
<!-- Fecha Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('fecha')}">
    {!! Form::label('fecha', 'Fecha') !!}
    {!! Form::date('fecha', null, ['class' => 'form-control','v-model' => 'selectedItem.fecha']) !!}
    <span class="help-block" v-show="errors.has('fecha')">(% errors.first('fecha') %)</span>
</div>

<!-- Visible Home Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('visible_home')}">
    {!! Form::label('visible_home', 'Visible Home') !!}
    <select v-model="selectedItem.visible_home" class="form-control" name="visible_home" v-validate="'required'" data-vv-validate-on="'none'">
        <!--option :value="null">Seleccione</option-->
        <option v-for="(item,index) in info.visibleHome" :value="item.key">(% item.value %)</option>
    </select>    
    <span class="help-block" v-show="errors.has('visible_home')">(% errors.first('visible_home') %)</span>
</div>

<!-- Orden Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('orden')}">
    {!! Form::label('orden', 'Orden') !!}
    {!! Form::number('orden', null, ['class' => 'form-control','v-model' => 'selectedItem.orden']) !!}
    <span class="help-block" v-show="errors.has('orden')">(% errors.first('orden') %)</span>
</div>

<div class="form-group col-sm-3" :class="{'has-error': errors.has('enabled')}">
    {!! Form::label('enabled', 'Visible') !!}<br>
    <switch-button v-model="selectedItem.enabled" theme="bootstrap" type-bold="true"></switch-button>
    <span class="help-block" v-show="errors.has('enabled')">(% errors.first('enabled') %)</span>
</div>
<div class="clearfix"></div>
</template>