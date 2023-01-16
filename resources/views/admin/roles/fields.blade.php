<!-- Name Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('name')}">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control','v-model' => 'selectedItem.name','v-validate' => "'required'",'data-vv-validate-on' => 'none']) !!}
    <span class="help-block" v-show="errors.has('name')">(% errors.first('name') %)</span>
</div>

<div class="col-sm-12">
	{!! Form::label('perm', 'Permisos') !!}
	<div class="table-responsive no-padding">
		<table class="table table-condensed">
		    <tbody>
		    	<tr>
			      <th style="border-top:none;border-bottom: 1px solid #f4f4f4;"></th>
			      <th class="text-center" style="width: 80px;border-top:none;border-bottom: 1px solid #f4f4f4;background-color: #f4f4f4;">Editar</th>
			      <th class="text-center" style="width: 80px;border-top:none;border-bottom: 1px solid #f4f4f4;background-color: #f4f4f4;">Ver</th>
			    </tr>
			    <tr v-for="(item,key) in info.permisos">
			      <td>(% key %)</td>
			      <td v-for="perm in item" class="text-center" style="background-color: #fff;">
			      	<input type="checkbox" :value="perm" v-model="selectedItem.permissions">
			      </td>
			    </tr>
		  </tbody>
		</table>
	</div>
</div>