<!-- Email Field -->
<div class="form-group col-sm-6" :class="{'has-error': errors.has('email')}">
    {!! Form::label('email', 'Email') !!}
    {!! Form::text('email', null, ['class' => 'form-control','v-model' => 'selectedItem.email']) !!}
    <span class="help-block" v-show="errors.has('email')">(% errors.first('email') %)</span>
</div>
<div class="clearfix"></div>