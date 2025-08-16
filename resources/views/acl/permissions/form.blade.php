<div class="form-group col-md-6">
    {{ html()->label()->for('permission_group_id')->text('Permission Group')->class('form-label required-input') }}
    {{ html()->select('permission_group_id', $groups, null)->class('form-select')->classIf($errors->has('permission_group_id'), 'error')->placeholder('Permission Group')->required() }}
    {!! $errors->first('permission_group_id', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('name')->text('Name')->class('form-label required-input') }}
    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('Name')->maxlength(50)->required() }}
    {!! $errors->first('name', '<label class="error">:message</label>') !!}
</div>
