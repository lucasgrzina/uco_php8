<!-- Registrado Id Field -->
<div class="form-group col-sm-2" :class="{'has-error': errors.has('created_at')}">
    {!! Form::label('created_at', 'Fecha') !!}
    <span class="form-control">(% selectedItem.created_at | dateFormat %)</span>
</div>
<!-- Registrado Id Field -->
<div class="form-group col-sm-5" :class="{'has-error': errors.has('registrado_id')}">
    {!! Form::label('registrado_id', 'Usuario') !!}
    <span class="form-control">(% selectedItem.registrado.usuario %)</span>
</div>

<!-- Email Field -->
<div class="form-group col-sm-5" :class="{'has-error': errors.has('email')}">
    {!! Form::label('email', 'Email') !!}
    <span class="form-control">(% selectedItem.email %)</span>
    <span class="help-block" v-show="errors.has('email')">(% errors.first('email') %)</span>
</div>

<div class="col-sm-12">
    <hr>
    <h3>Datos de envío</h3>
</div>
<div class="form-group col-sm-8">
    {!! Form::label('registrado_id', 'Dirección') !!}
    <span class="form-control">(% selectedItem.direccion %)</span>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('departamento', 'Depto') !!}
    <span class="form-control">(% selectedItem.departamento %)</span>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('registrado_id', 'Ciudad') !!}
    <span class="form-control">(% selectedItem.ciudad %)</span>
</div>
<div class="form-group col-md-3">
    {!! Form::label('registrado_id', 'CP') !!}
    <span class="form-control">(% selectedItem.cp %)</span>
</div>

<div class="form-group col-md-3">
    {!! Form::label('registrado_id', 'Provincia') !!}
    <span class="form-control">(% selectedItem.provincia %)</span>
</div>
<div class="form-group col-md-3">
    {!! Form::label('registrado_id', 'País') !!}
    <span class="form-control">(% selectedItem.pais ? selectedItem.pais.nombre : '--' %)</span>
</div>
<div class="form-group col-md-12">
    {!! Form::label('info_adicional', 'Info Adicional') !!}
    <span class="form-control">(% selectedItem.info_adicional %)</span>
</div>

<div class="col-sm-12">
    <hr>
    <h3>Datos del destinatario</h3>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('registrado_id', 'Nombre') !!}
    <span class="form-control">(% selectedItem.nombre %)</span>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('registrado_id', 'Apellido') !!}
    <span class="form-control">(% selectedItem.apellido %)</span>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('registrado_id', 'DNI') !!}
    <span class="form-control">(% selectedItem.dni %)</span>
</div>

<div class="col-sm-12">
    <hr>
    <h3>Datos de facturacion</h3>
</div>

<template v-if="selectedItem.tipo_factura == 'A'">
    <div class="form-group col-sm-12">
        {!! Form::label('registrado_id', 'Tipo Factura') !!}
        <span class="form-control">Factura A</span>
    </div>

    <div class="form-group col-sm-8">
        {!! Form::label('registrado_id', 'Razón Social') !!}
        <span class="form-control">(% selectedItem.razon_social %)</span>
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('registrado_id', 'CUIT') !!}
        <span class="form-control">(% selectedItem.cuit %)</span>
    </div>
</template>
<template v-else>
    <div class="form-group col-sm-12">
        {!! Form::label('registrado_id', 'Tipo Factura') !!}
        <span class="form-control">Consumidor Final</span>
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('registrado_id', 'Nombre') !!}
        <span class="form-control">(% selectedItem.nombre_fc %)</span>
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('registrado_id', 'Apellido') !!}
        <span class="form-control">(% selectedItem.apellido_fc %)</span>
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('registrado_id', 'DNI') !!}
        <span class="form-control">(% selectedItem.dni_fc %)</span>
    </div>

</template>
<template v-if="selectedItem.direccion_fc">
    <div class="form-group col-sm-8">
        {!! Form::label('registrado_id', 'Dirección') !!}
        <span class="form-control">(% selectedItem.direccion_fc %)</span>
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('registrado_id', 'Ciudad') !!}
        <span class="form-control">(% selectedItem.ciudad_fc %)</span>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('registrado_id', 'CP') !!}
        <span class="form-control">(% selectedItem.cp_fc %)</span>
    </div>

    <div class="form-group col-md-4">
        {!! Form::label('registrado_id', 'Provincia') !!}
        <span class="form-control">(% selectedItem.provincia_fc %)</span>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('registrado_id', 'País') !!}
        <span class="form-control">(% selectedItem.pais ? selectedItem.pais.nombre : '--' %)</span>
    </div>
</template>
<div class="col-sm-12">
    <hr>
    <h3>Comentario</h3>
</div>
<div class="form-group col-md-12">
    {!! Form::label('registrado_id', 'Comentario') !!}
    <span class="form-control">(% selectedItem.comentarios %)</span>
</div>

<div class="col-sm-12">
    <hr>
    <h3>Items del pedido</h3>
</div>


<div class="clearfix"></div>
