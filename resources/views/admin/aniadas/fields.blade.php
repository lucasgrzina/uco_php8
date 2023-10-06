<template  v-if="!selectedItem.lang">
<!-- Anio Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('anio')}">
    {!! Form::label('anio', 'Anio') !!}
    {!! Form::text('anio', null, ['class' => 'form-control','v-model' => 'selectedItem.anio']) !!}
    <span class="help-block" v-show="errors.has('anio')">(% errors.first('anio') %)</span>
</div>
<!-- Sku Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('sku')}">
    {!! Form::label('sku', 'Sku') !!}
    {!! Form::text('sku', null, ['class' => 'form-control','v-model' => 'selectedItem.sku']) !!}
    <span class="help-block" v-show="errors.has('sku')">(% errors.first('sku') %)</span>
</div>
<!-- Vino Id Field -->
<div class="form-group col-sm-5" :class="{'has-error': errors.has('vino_id')}">
    {!! Form::label('vino_id', 'Vino') !!}
    {!! Form::text('vino_id', null, ['class' => 'form-control','v-model' => 'selectedItem.vino.titulo', ':disabled' => 'true']) !!}
    <span class="help-block" v-show="errors.has('vino_id')">(% errors.first('vino_id') %)</span>
</div>


<div class="clearfix"></div>

<!-- Stock Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('stock')}">
    {!! Form::label('stock', 'Stock') !!}
    {!! Form::text('stock', null, ['class' => 'form-control','v-model' => 'selectedItem.stock']) !!}
    <span class="help-block" v-show="errors.has('stock')">(% errors.first('stock') %)</span>
</div>

<!-- Precio Pesos Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('precio_pesos')}">
    {!! Form::label('precio_pesos', 'Precio Pesos') !!}
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">$</span>
        {!! Form::number('precio_pesos', null, [
                'class' => 'form-control',
                'v-model' => 'selectedItem.precio_pesos',
                'min' => '0.00',
                'max' => '100.00',
                'step' => '0.01'
            ]) !!}
    </div>
    <span class="help-block" v-show="errors.has('precio_pesos')">(% errors.first('precio_pesos') %)</span>
</div>

<!-- Precio Usd Field -->
<div class="form-group col-sm-4" :class="{'has-error': errors.has('precio_usd')}">
    {!! Form::label('precio_usd', 'Precio Usd') !!}
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">USD</span>
        {!! Form::number('precio_usd', null, [
            'class' => 'form-control',
            'v-model' => 'selectedItem.precio_usd',
            'min' => '0.00',
            'max' => '100.00',
            'step' => '0.01'
        ]) !!}
    </div>
    <span class="help-block" v-show="errors.has('precio_usd')">(% errors.first('precio_usd') %)</span>
</div>

<div class="clearfix"></div>
<!-- Ficha Field -->
<!-- Ficha Field -->
</template>
<div class="form-group col-sm-12" :class="{'has-error': errors.has('ficha')}">
    {!! Form::label('ficha', 'Ficha') !!}
    <div class="">

        <file-upload

            :multiple="false"
            :headers="_fuHeader"
            ref="uploadFicha"
            extensions="pdf"
            accept="application/pdf"
            input-id="ficha"
            v-model="files.ficha"
            post-action="{{ route('uploads.store-file') }}"
            @input-file="inputFicha"
            class="">
                <img class="img-responsive" src="{{ asset('admin/img/generic-upload.png') }}" v-if="!selectedItem.ficha" style="max-width: 100px;">
                <div style="width: 100%; text-align: left;"  v-else>
                    <i class="fa fa-file" style="font-size: 25px; margin-right: 5px;"> </i>
                   <span >
                    (% selectedItem.ficha_url %)</span>
                </div>
                <div class="progress m-t-5 m-b-0" v-if="files.ficha.length > 0">
                    <div class="progress-bar" :style="{ width: files.ficha[0].progress+'%' }"></div>
                </div>
        </file-upload>
    </div>
    <span class="help-block" v-show="errors.has('ficha')">(% errors.first('ficha') %)</span>
</div>
<!-- Descripcion Field -->
<div class="form-group col-sm-12 col-lg-12" :class="{'has-error': errors.has('descripcion')}">
    {!! Form::label('descripcion', 'Descripcion') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control','v-model' => 'selectedItem.descripcion']) !!}
    <span class="help-block" v-show="errors.has('descripcion')">(% errors.first('descripcion') %)</span>
</div>
<div class="clearfix"></div>
