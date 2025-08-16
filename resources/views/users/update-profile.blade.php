<x-admin-layout>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Update Profile" />
                    <!-- [ breadcrumb ] end -->
                    
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ basic-table ] start -->
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Personal details</h5>
                                        </div>
                                        {{ html()->modelForm($user, 'POST', route('update-profile'))->id('formValidation')->open() }}
                                            <div class="card-body row">
                                                <div class="form-group col-md-6">
                                                    {{ html()->label()->for('name')->text('Name')->class('form-label required-input') }}
                                                    {{ html()->text('name')->class('form-control')->classIf($errors->has('name'), 'error')->placeholder('Name')->maxlength(50)->required() }}
                                                    {!! $errors->first('name', '<label class="error">:message</label>') !!}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ html()->label()->for('username')->text('User Name/CNIC')->class('form-label required-input') }}
                                                    {{ html()->text('username')->class('form-control')->classIf($errors->has('username'), 'error')->placeholder('User Name/CNIC')->attribute('readonly', true) }}
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
                                                    {{ html()->label()->for('designation')->text('Designation')->class('form-label') }}
                                                    {{ html()->text('designation')->class('form-control')->classIf($errors->has('designation'), 'error')->placeholder('Designation')->maxlength(50) }}
                                                    {!! $errors->first('designation', '<label class="error">:message</label>') !!}
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary mr-2">Update</button>
                                                <button type="button" onclick="window.location='{{ URL::previous() }}'" class="btn btn-secondary">Cancel</button>
                                            </div>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                                <!-- [ basic-table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->


    @push('scripts')    
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('#formValidation').validate();
            $('.mobile-mask').mask('0000-0000000');
        });
    </script>
    @endpush

</x-admin-layout>