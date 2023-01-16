<!-- Codigo Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('codigo')}">
    {!! Form::label('codigo', 'Codigo') !!}
    {!! Form::text('codigo', null, ['class' => 'form-control','v-model' => 'selectedItem.codigo']) !!}
    <span class="help-block" v-show="errors.has('codigo')">(% errors.first('codigo') %)</span>
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('nombre')}">
    {!! Form::label('nombre', 'Nombre') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control','v-model' => 'selectedItem.nombre']) !!}
    <span class="help-block" v-show="errors.has('nombre')">(% errors.first('nombre') %)</span>
</div>
<div class="clearfix"></div>