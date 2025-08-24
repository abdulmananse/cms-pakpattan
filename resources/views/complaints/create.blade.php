<x-admin-layout>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Register Complaint" />
                    <!-- [ breadcrumb ] end -->
                    
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ basic-table ] start -->
                                <div class="col-xl-12">
                                    <div class="card card-custom gutter-b example example-compact">
                                        <!--begin::Form-->
                                        {{ html()->form('POST', route('complaints.store'))->id('formValidation')->attribute('enctype', 'multipart/form-data')->open() }}
                                            <div class="card-body row">
                                                @include ('complaints.form')
                                            </div>
                                            <div class="card-footer d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#username').mask('00000-0000000-0');    
            $('#mobile').mask('0300-0000000');    
        });
    </script>
    @endpush

</x-admin-layout>