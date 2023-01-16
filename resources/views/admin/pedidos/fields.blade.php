<!-- Registrado Id Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('registrado_id')}">
    {!! Form::label('registrado_id', 'Registrado Id') !!}
    {!! Form::text('registrado_id', null, ['class' => 'form-control','v-model' => 'selectedItem.registrado_id']) !!}
    <span class="help-block" v-show="errors.has('registrado_id')">(% errors.first('registrado_id') %)</span>
</div>

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
<div class="clearfix"></div>