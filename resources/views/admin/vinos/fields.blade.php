<template  v-if="!selectedItem.lang">
<div class="form-group col-sm-6" :class="{'has-error': errors.has('coleccion')}">
    {!! Form::label('coleccion', 'Colección') !!}
    <select v-model="selectedItem.coleccion" class="form-control" name="coleccion" v-validate="'required'" data-vv-validate-on="'none'" :disabled="selectedItem.id > 0">
        <option :value="null">Seleccione</option>
        <option v-for="(nombre,key) in info.colecciones" :value="key">(% nombre %)</option>
    </select>
    <span class="help-block" v-show="errors.has('coleccion')">(% errors.first('coleccion') %)</span>
</div>
<div class="clearfix"></div>

</template>
<!-- Imagen Field -->
<div class="form-group col-sm-2" :class="{'has-error': errors.has('imagen')}">
    {!! Form::label('imagen', 'Imagen') !!}
    <div class="thumb-wrap">
        <button-type v-if="selectedItem.imagen" type="remove-list" @click="removeFile('imagen')"></button-type>
        <file-upload

            :multiple="false"
            :headers="_fuHeader"
            ref="uploadImagen"
            extensions="gif,jpg,jpeg,png,webp,svg"
            accept="image/png,image/gif,image/jpeg,image/webp,image/svg"
            input-id="imagen"
            v-model="files.imagen"
            post-action="{{ route('uploads.store-file') }}"
            @input-file="inputImagen"
            class="thumbnail">
                <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.imagen_url">
                <img class="img-responsive" :src="selectedItem.imagen_url" v-else>
                <div class="progress m-t-5 m-b-0" v-if="files.imagen.length > 0">
                    <div class="progress-bar" :style="{ width: files.imagen[0].progress+'%' }"></div>
                </div>
        </file-upload>
    </div>
    <span class="help-block" v-show="errors.has('imagen')">(% errors.first('imagen') %)</span>
</div>
<!-- Titulo Field -->
<div class="form-group col-sm-10" :class="{'has-error': errors.has('titulo')}">
    {!! Form::label('titulo', 'Titulo') !!}
    {!! Form::text('titulo', null, ['class' => 'form-control','v-model' => 'selectedItem.titulo']) !!}
    <span class="help-block" v-show="errors.has('titulo')">(% errors.first('titulo') %)</span>
</div>


<div class="clearfix"></div>
<template  v-if="!selectedItem.lang">
<!-- Peso Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('peso')}">
    {!! Form::label('peso', 'Peso') !!}
    {!! Form::number('peso', null, ['class' => 'form-control','v-model' => 'selectedItem.peso']) !!}
    <span class="help-block" v-show="errors.has('peso')">(% errors.first('peso') %)</span>
</div>

<!-- Largo Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('largo')}">
    {!! Form::label('largo', 'Largo') !!}
    {!! Form::number('largo', null, ['class' => 'form-control','v-model' => 'selectedItem.largo']) !!}
    <span class="help-block" v-show="errors.has('largo')">(% errors.first('largo') %)</span>
</div>

<!-- Ancho Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('ancho')}">
    {!! Form::label('ancho', 'Ancho') !!}
    {!! Form::number('ancho', null, ['class' => 'form-control','v-model' => 'selectedItem.ancho']) !!}
    <span class="help-block" v-show="errors.has('ancho')">(% errors.first('ancho') %)</span>
</div>

<!-- Alto Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('alto')}">
    {!! Form::label('alto', 'Alto') !!}
    {!! Form::number('alto', null, ['class' => 'form-control','v-model' => 'selectedItem.alto']) !!}
    <span class="help-block" v-show="errors.has('alto')">(% errors.first('alto') %)</span>
</div>
<div class="clearfix"></div>
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
<div class="form-group col-sm-3" :class="{'has-error': errors.has('vendible')}">
    {!! Form::label('vendible', 'Vendible') !!}<br>
    <switch-button v-model="selectedItem.vendible" theme="bootstrap" type-bold="true"></switch-button>
    <span class="help-block" v-show="errors.has('vendible')">(% errors.first('vendible') %)</span>
</div>
</template>
