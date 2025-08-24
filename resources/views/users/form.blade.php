<div class="form-group col-md-6">
    {{ html()->label()->for('name')->text('Name')->class('form-label required-input') }}
    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('Name')->maxlength(50)->required() }}
    {!! $errors->first('name', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('username')->text('User Name/CNIC')->class('form-label required-input') }}
    {{ html()->text('username')->class('form-control')->classIf($errors->has('username'), 'error')->placeholder('User Name/CNIC')->maxlength(50)->required() }}
    {!! $errors->first('username', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('email')->text('Email')->class('form-label') }}
    {{ html()->email('email')->class('form-control')->classIf($errors->has('email'), 'error')->placeholder('Email')->maxlength(100) }}
    {!! $errors->first('email', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('mobile')->text('Mobile')->class('form-label') }}
    {{ html()->text('mobile')->value(old('mobile', @$user->mobile))->class('form-control mobile-mask')->classIf($errors->has('mobile'), 'error')->placeholder('03xx-xxxxxxx') }}
    {!! $errors->first('mobile', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('role')->text('Role')->class('form-label required-input') }}
    {{ html()->select('role', $roles, null)->class('form-select')->classIf($errors->has('role'), 'error')->placeholder('Select Role')->required() }}
    {!! $errors->first('role', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('department_id')->text('Department')->class('form-label') }}
    {{ html()->select('department_id', $departments, null)->class('form-select')->placeholder('Select Department') }}
    {!! $errors->first('department_id', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('source_id')->text('Source')->class('form-label') }}
    {{ html()->select('source_id', $sources, null)->class('form-select')->placeholder('Select Source') }}
    {!! $errors->first('source_id', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('designation')->text('Designation')->class('form-label') }}
    {{ html()->text('designation')->class('form-control')->classIf($errors->has('designation'), 'error')->placeholder('Designation')->maxlength(50) }}
    {!! $errors->first('designation', '<label class="error">:message</label>') !!}
</div>
@if(@$user)
@else
<div class="form-group col-md-6">
    {{ html()->label()->for('password')->text('Password')->class('form-label required-input') }}
    {{ html()->password('password')->class('form-control')->classIf($errors->has('password'), 'error')->placeholder('Password')->required() }}
    {!! $errors->first('password', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('password_confirmation')->text('Confirm Password')->class('form-label required-input') }}
    {{ html()->password('password_confirmation')->class('form-control')->classIf($errors->has('password_confirmation'), 'error')->placeholder('Confirm Password')->required() }}
    {!! $errors->first('password_confirmation', '<label class="error">:message</label>') !!}
</div>
@endif


@push('scripts')    
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $.validator.addMethod("noSpace", function(value, element) { 
                return value.indexOf(" ") < 0 && value != ""; 
            }, "No space please and don't leave it empty");

            $('#formValidation').validate({
                rules: {
                    username: {
                        noSpace: true
                    }
                }
            });

            $('.mobile-mask').mask('0000-0000000');    
        });
    </script>
@endpush