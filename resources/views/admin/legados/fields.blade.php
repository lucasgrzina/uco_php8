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
<!-- Cuerpo Field -->
<div class="form-group col-sm-12" :class="{'has-error': errors.has('cuerpo')}">
    {!! Form::label('cuerpo', 'Cuerpo') !!}
    <vue-mce v-model="selectedItem.cuerpo" :config="tinyConfig"/>
    <span class="help-block" v-show="errors.has('cuerpo')">(% errors.first('cuerpo') %)</span>
</div>


<div class="clearfix"></div>
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