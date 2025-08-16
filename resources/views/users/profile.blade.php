<x-admin-layout>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Profile" />
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
                                            <a href="{{ route('edit-profile') }}" class="btn btn-primary btn-sm rounded m-0 float-end" >
                                                <i class="feather icon-edit"></i>
                                            </a>
                                        </div>
                                        <div class="card-body border-top pro-det-edit collapse show" id="pro-det-edit-1">
                                            <form>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label font-weight-bolder">Full Name</label>
                                                    <div class="col-sm-9">
                                                        {{ $user->name }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label font-weight-bolder">Username/CNIC</label>
                                                    <div class="col-sm-9">
                                                        {{ $user->username }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label font-weight-bolder">Email</label>
                                                    <div class="col-sm-9">
                                                        {{ $user->email }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label font-weight-bolder">Mobile</label>
                                                    <div class="col-sm-9">
                                                        {{ $user->mobile }}
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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