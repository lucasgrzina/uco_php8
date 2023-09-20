<!-- Nombre Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('key')}">
    {!! Form::label('clave', 'Identificador') !!}
    {!! Form::text('clave', null, ['class' => 'form-control','v-model' => 'selectedItem.clave']) !!}
    <span class="help-block" v-show="errors.has('clave')">(% errors.first('clave') %)</span>
</div>

<!-- Apellido Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('valor')}">
    {!! Form::label('valor', 'Valor') !!}
    {!! Form::text('valor', null, ['class' => 'form-control','v-model' => 'selectedItem.valor']) !!}
    <span class="help-block" v-show="errors.has('valor')">(% errors.first('valor') %)</span>
</div>

<div class="clearfix"></div>
