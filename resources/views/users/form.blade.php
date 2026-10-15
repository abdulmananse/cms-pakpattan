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
    {{ html()->label()->for('roles')->text('Role(s)')->class('form-label required-input') }}
    {{ html()->select('roles[]', $roles)->class('form-select select2-roles')->attribute('multiple')->value(old('roles', $selectedRoles)) }}
    {!! $errors->first('roles', '<label class="error">:message</label>') !!}
    {!! $errors->first('roles.*', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('source_id')->text('Source')->class('form-label') }}
    {{ html()->select('source_id', $sources, null)->class('form-select')->placeholder('Select Source') }}
    {!! $errors->first('source_id', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('department_ids')->text('Department')->class('form-label') }}
    {{ html()->select('department_ids[]', $departments)->class('form-select')->attribute('multiple')->value(old('department_ids', $selectedDepartments)) }}
    {!! $errors->first('department_ids', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('permission_ids')->text('Permission')->class('form-label') }}
    {{ html()->select('permission_ids[]', $permissions)->class('form-select')->attribute('multiple')->value(old('permission_ids', $selectedPermissions)) }}
    {!! $errors->first('permission_ids', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('designation')->text('Designation')->class('form-label') }}
    {{ html()->text('designation')->class('form-control')->classIf($errors->has('designation'), 'error')->placeholder('Designation')->maxlength(50) }}
    {!! $errors->first('designation', '<label class="error">:message</label>') !!}
</div>

@php($requiredClass = 'required-input')
@if(@$user)
@php($requiredClass = '')
<h6 class="mt-4">Update Password</h6>
<small>If you leave this field empty then password will not be updated</small>
@endif

<div class="form-group col-md-6">
    {{ html()->label()->for('password')->text('Password')->class('form-label ' . $requiredClass) }}
    {{ html()->password('password')->class('form-control')->classIf($errors->has('password'), 'error')->placeholder('Password') }}
    {!! $errors->first('password', '<label class="error">:message</label>') !!}
</div>
<div class="form-group col-md-6">
    {{ html()->label()->for('password_confirmation')->text('Confirm Password')->class('form-label ' . $requiredClass) }}
    {{ html()->password('password_confirmation')->class('form-control')->classIf($errors->has('password_confirmation'), 'error')->placeholder('Confirm Password') }}
    {!! $errors->first('password_confirmation', '<label class="error">:message</label>') !!}
</div>

@push('scripts')    
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $.validator.addMethod("noSpace", function(value, element) { 
                return value.indexOf(" ") < 0 && value != ""; 
            }, "No space please and don't leave it empty");

            $('#formValidation').validate({
                ignore: ':hidden:not(.select2-roles)',
                rules: {
                    username: {
                        noSpace: true
                    },
                    'roles[]': {
                        required: true
                    }
                },
                messages: {
                    'roles[]': {
                        required: "Please select at least one role."
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('select2-roles') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $('.mobile-mask').mask('0000-0000000');

            if ($.fn.select2) {
                $('.select2-roles').select2({
                    placeholder: "Select Role(s)",
                    width: '100%'
                });
                $('.select2-roles').on('change', function () {
                    $(this).valid();
                });
            }
        });
    </script>
@endpush