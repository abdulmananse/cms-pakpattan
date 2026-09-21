<div class="form-group col-md-6">
    {{ html()->label()->for('name')->text('Category Name')->class('form-label required-input') }}
    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('e.g. District Administration')->maxlength(255)->required() }}
    {!! $errors->first('name', '<label class="error">:message</label>') !!}
</div>

<div class="form-group col-md-6">
    {{ html()->label()->for('is_active')->text('Status')->class('form-label required-input') }}
    {{ html()->select('is_active', ['1' => 'Active', '0' => 'Inactive'], old('is_active', @$contactCategory ? $contactCategory->is_active : 1))->class('form-select')->classIf($errors->has('is_active'), 'error')->required() }}
    {!! $errors->first('is_active', '<label class="error">:message</label>') !!}
</div>

<div class="form-group col-md-12">
    {{ html()->label()->for('description')->text('Description')->class('form-label') }}
    {{ html()->textarea('description')->class('form-control')->classIf($errors->has('description'), 'error')->placeholder('Category details or notes (optional)')->rows(3) }}
    {!! $errors->first('description', '<label class="error">:message</label>') !!}
</div>
