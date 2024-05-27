<div class="form-group col-sm-2" :class="{'has-error': errors.has('codigo')}" v-if="!selectedItem.id">
    {!! Form::label('codigo', 'Código') !!}
    {!! Form::text('codigo', null, ['class' => 'form-control','v-model' => 'selectedItem.codigo']) !!}
    <span class="help-block" v-show="errors.has('codigo')">(% errors.first('codigo') %)</span>
</div>
<!-- Titulo Field -->
<div class="form-group col-sm-8" :class="{'has-error': errors.has('titulo')}">
    {!! Form::label('titulo', 'Titulo') !!}
    <span class="form-control" style="background-color: #ddd;" >(% selectedItem.titulo %)</span>
    <span class="help-block" v-show="errors.has('titulo')">(% errors.first('titulo') %)</span>
</div>
<div class="clearfix"></div>
<!-- Foto Field -->
<div class="form-group col-sm-2" :class="{'has-error': errors.has('imagen_home')}">
    {!! Form::label('imagen_home', 'Imagen') !!}
    <div class="thumb-wrap">
        <button-type v-if="selectedItem.imagen_home" type="remove-list" @click="removeFile('imagen_home')"></button-type>
        <file-upload

            :multiple="false"
            :headers="_fuHeader"
            ref="uploadImagenHome"
            extensions="gif,jpg,jpeg,png,webp,svg"
            accept="image/png,image/gif,image/jpeg,image/webp,image/svg"
            input-id="imagen_home"
            v-model="files.imagen_home"
            post-action="{{ route('uploads.store-file') }}"
            @input-file="inputImagenHome"
            class="thumbnail">
                <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.imagen_home_url">
                <img class="img-responsive" :src="selectedItem.imagen_home_url" v-else>
                <div class="progress m-t-5 m-b-0" v-if="files.imagen_home.length > 0">
                    <div class="progress-bar" :style="{ width: files.imagen_home[0].progress+'%' }"></div>
                </div>
        </file-upload>
    </div>
    <span class="help-block" v-show="errors.has('imagen_home')">(% errors.first('imagen_home') %)</span>
</div>

<div class="form-group col-sm-2" :class="{'has-error': errors.has('imagen_home')}">
    {!! Form::label('imagen_interna', 'Imagen interna') !!}
    <div class="thumb-wrap">
        <button-type v-if="selectedItem.imagen_interna" type="remove-list" @click="removeFile('imagen_interna')"></button-type>
        <file-upload

            :multiple="false"
            :headers="_fuHeader"
            ref="uploadImagenInterna"
            extensions="gif,jpg,jpeg,png,webp,svg"
            accept="image/png,image/gif,image/jpeg,image/webp,image/svg"
            input-id="imagen_interna"
            v-model="files.imagen_interna"
            post-action="{{ route('uploads.store-file') }}"
            @input-file="inputImagenInterna"
            class="thumbnail">
                <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.imagen_interna_url">
                <img class="img-responsive" :src="selectedItem.imagen_interna_url" v-else>
                <div class="progress m-t-5 m-b-0" v-if="files.imagen_interna.length > 0">
                    <div class="progress-bar" :style="{ width: files.imagen_interna[0].progress+'%' }"></div>
                </div>
        </file-upload>
    </div>
    <span class="help-block" v-show="errors.has('imagen_interna')">(% errors.first('imagen_interna') %)</span>
</div>
