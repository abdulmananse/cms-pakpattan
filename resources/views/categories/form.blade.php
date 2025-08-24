<div class="form-group col-md-6">
    {{ html()->label()->for('code')->text('Code')->class('form-label required-input') }}
    {{ html()->text('code')->class('form-control')->classIf($errors->has('code'), 'error')->placeholder('Code')->maxlength(10)->required() }}
    {!! $errors->first('code', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('name')->text('Name')->class('form-label required-input') }}
    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('Name')->maxlength(50)->required() }}
    {!! $errors->first('name', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('ordering')->text('Ordering')->class('form-label required-input') }}
    {{ html()->number('ordering')->class('form-control')->classIf($errors->has('ordering'), 'error')->placeholder('Ordering')->required() }}
    {!! $errors->first('ordering', '<label class="error">:message</label>') !!}
</div>