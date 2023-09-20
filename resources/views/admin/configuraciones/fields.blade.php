<!-- Nombre Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('nombre')}">
    {!! Form::label('nombre', 'Nombre') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control','v-model' => 'selectedItem.nombre']) !!}
    <span class="help-block" v-show="errors.has('nombre')">(% errors.first('nombre') %)</span>
</div>

<!-- Apellido Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('apellido')}">
    {!! Form::label('apellido', 'Apellido') !!}
    {!! Form::text('apellido', null, ['class' => 'form-control','v-model' => 'selectedItem.apellido']) !!}
    <span class="help-block" v-show="errors.has('apellido')">(% errors.first('apellido') %)</span>
</div>

<!-- Email Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('email')}">
    {!! Form::label('email', 'Email') !!}
    {!! Form::text('email', null, ['class' => 'form-control','v-model' => 'selectedItem.email']) !!}
    <span class="help-block" v-show="errors.has('email')">(% errors.first('email') %)</span>
</div>

<!-- Pais Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('pais')}">
    {!! Form::label('pais', 'Pais') !!}
    {!! Form::text('pais', null, ['class' => 'form-control','v-model' => 'selectedItem.pais']) !!}
    <span class="help-block" v-show="errors.has('pais')">(% errors.first('pais') %)</span>
</div>

<!-- Pais Id Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('pais_id')}">
    {!! Form::label('pais_id', 'Pais Id') !!}
    {!! Form::text('pais_id', null, ['class' => 'form-control','v-model' => 'selectedItem.pais_id']) !!}
    <span class="help-block" v-show="errors.has('pais_id')">(% errors.first('pais_id') %)</span>
</div>

<!-- Tel Prefijo Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('tel_prefijo')}">
    {!! Form::label('tel_prefijo', 'Tel Prefijo') !!}
    {!! Form::text('tel_prefijo', null, ['class' => 'form-control','v-model' => 'selectedItem.tel_prefijo']) !!}
    <span class="help-block" v-show="errors.has('tel_prefijo')">(% errors.first('tel_prefijo') %)</span>
</div>

<!-- Tel Numero Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('tel_numero')}">
    {!! Form::label('tel_numero', 'Tel Numero') !!}
    {!! Form::text('tel_numero', null, ['class' => 'form-control','v-model' => 'selectedItem.tel_numero']) !!}
    <span class="help-block" v-show="errors.has('tel_numero')">(% errors.first('tel_numero') %)</span>
</div>

<!-- Mensaje Field -->
<div class="form-group col-sm-12 col-lg-12" :class="{'has-error': errors.has('mensaje')}">
    {!! Form::label('mensaje', 'Mensaje') !!}
    {!! Form::textarea('mensaje', null, ['class' => 'form-control','v-model' => 'selectedItem.mensaje']) !!}
    <span class="help-block" v-show="errors.has('mensaje')">(% errors.first('mensaje') %)</span>
</div>

<!-- Recibir Info Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('recibir_info')}">
    {!! Form::label('recibir_info', 'Recibir Info') !!}
    {!! Form::text('recibir_info', null, ['class' => 'form-control','v-model' => 'selectedItem.recibir_info']) !!}
    <span class="help-block" v-show="errors.has('recibir_info')">(% errors.first('recibir_info') %)</span>
</div>
<div class="clearfix"></div>