<x-admin-layout>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Change Password" />
                    <!-- [ breadcrumb ] end -->
                    
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ basic-table ] start -->
                                <div class="col-xl-12">
                                    <div class="card card-custom gutter-b example example-compact">
                                        <!--begin::Form-->
                                        {{ html()->modelForm($user, 'POST', route('update-password'))->id('formValidation')->open() }}
                                            <div class="card-body">
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
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary mr-2">Update Password</button>
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
    <script type="text/javascript">
        $('document').ready(function () {
            $('#formValidation').validate();
        });
    </script>
    @endpush

</x-admin-layout>