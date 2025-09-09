<div class="form-group col-md-6">
    {{ html()->label()->for('name')->text('Name')->class('form-label required-input') }}
    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('Name')->maxlength(50)->required() }}
    {!! $errors->first('name', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('username')->text('CNIC')->class('form-label') }}
    {{ html()->text('username')->class('form-control')->classIf($errors->has('username'), 'error')->placeholder('xxxxx-xxxxxxx-x') }}
    {!! $errors->first('username', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('mobile')->text('Mobile')->class('form-label') }}
    {{ html()->text('mobile')->class('form-control')->classIf($errors->has('mobile'), 'error')->placeholder('03xx-xxxxxxx') }}
    {!! $errors->first('mobile', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('email')->text('Email')->class('form-label') }}
    {{ html()->email('email')->class('form-control')->classIf($errors->has('email'), 'error')->placeholder('Email') }}
    {!! $errors->first('email', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('address')->text('Address')->class('form-label') }}
    {{ html()->textarea('address')->class('form-control')->classIf($errors->has('address'), 'error')->placeholder('Address')->maxlength(500) }}
    {!! $errors->first('address', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('category')->text('Complaint Category')->class('form-label required-input') }}
    {{ html()->select('category', $categories, null)->class('form-select select2')->placeholder('Complaint Category')->required() }}
    {!! $errors->first('category', '<label class="error">:message</label>') !!}
    <label id="category-error" class="error" for="category"></label>
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('department_id')->text('Department')->class('form-label required-input') }}
    {{ html()->select('department_id', $departments, null)->class('form-select select2')->placeholder('Department')->required() }}
    {!! $errors->first('department_id', '<label class="error">:message</label>') !!}
    <label id="department_id-error" class="error" for="department_id"></label>
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('description')->text('Description')->class('form-label required-input') }}
    {{ html()->textarea('description')->class('form-control')->classIf($errors->has('description'), 'error')->placeholder('Description')->required() }}
    {!! $errors->first('description', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('location')->text('Location')->class('form-label required-input') }}
    {{ html()->text('location')->class('form-control')->classIf($errors->has('location'), 'error')->placeholder('Location')->maxlength(100)->required() }}
    {!! $errors->first('location', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('source')->text('Source')->class('form-label required-input') }}
    {{ html()->select('source', $sources, null)->class('form-select')->placeholder('Complaint Source')->required() }}
    {!! $errors->first('source', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('date')->text('Complaint Date')->class('form-label required-input') }}
    {{ html()->text('date', null)->class('form-control date-mask')->placeholder('DD/MM/YYYY')->required()->attribute('readonly') }}
    {!! $errors->first('date', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('attachment')->text('Attachment')->class('form-label') }} <br/>
    {{ html()->file('attachment') }}
    {!! $errors->first('attachment', '<label class="error">:message</label>') !!}
</div>
