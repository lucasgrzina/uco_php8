<!-- Unidades Field -->
<div class="form-group col-sm-2" :class="{'has-error': errors.has('unidades')}">
    {!! Form::label('unidades', 'Unidades') !!}
    {!! Form::number('unidades', null, ['class' => 'form-control','v-model' => 'selectedItem.unidades']) !!}
    <span class="help-block" v-show="errors.has('unidades')">(% errors.first('unidades') %)</span>
</div>
<div class="clearfix"></div>
<!-- Alto Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('alto')}">
    {!! Form::label('alto', 'Alto') !!}
    {!! Form::number('alto', null, ['class' => 'form-control','v-model' => 'selectedItem.alto','step' => '0.1','max' => '999.9','min' => '0.1']) !!}
    <span class="help-block" v-show="errors.has('alto')">(% errors.first('alto') %)</span>
</div>

<!-- Largo Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('largo')}">
    {!! Form::label('largo', 'Largo') !!}
    {!! Form::number('largo', null, ['class' => 'form-control','v-model' => 'selectedItem.largo','step' => '0.1','max' => '999.9','min' => '0.1']) !!}
    <span class="help-block" v-show="errors.has('largo')">(% errors.first('largo') %)</span>
</div>

<!-- Ancho Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('ancho')}">
    {!! Form::label('ancho', 'Ancho') !!}
    {!! Form::number('ancho', null, ['class' => 'form-control','v-model' => 'selectedItem.ancho','step' => '0.1','max' => '999.9','min' => '0.1']) !!}
    <span class="help-block" v-show="errors.has('ancho')">(% errors.first('ancho') %)</span>
</div>

<!-- Peso Field -->
<div class="form-group col-sm-3" :class="{'has-error': errors.has('peso')}">
    {!! Form::label('peso', 'Peso') !!}
    {!! Form::number('peso', null, ['class' => 'form-control','v-model' => 'selectedItem.peso','step' => '0.1','max' => '999.9','min' => '0.1']) !!}
    <span class="help-block" v-show="errors.has('peso')">(% errors.first('peso') %)</span>
</div>
<div class="clearfix"></div>