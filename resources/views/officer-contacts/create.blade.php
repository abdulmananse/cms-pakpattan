<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="ADD OFFICER CONTACT" />
                    <!-- [ breadcrumb ] end -->

                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card card-custom gutter-b">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title font-weight-bold mb-0 text-primary">ADD OFFICER CONTACT</h4>
                                            <a href="{{ route('officer-contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="feather icon-arrow-left me-1"></i> Back to Listing
                                            </a>
                                        </div>

                                        {{ html()->form('POST', route('officer-contacts.store'))->id('formValidation')->acceptsFiles()->open() }}
                                            <div class="card-body row">
                                                @include('officer-contacts.form')
                                            </div>
                                            <div class="card-footer d-flex justify-content-end bg-light border-top">
                                                <a href="{{ route('officer-contacts.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save Officer Contact</button>
                                            </div>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        function previewOfficerImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function () {
            $('#formValidation').validate({
                rules: {
                    officer_name: { required: true, maxlength: 255 },
                    department_id: { required: true },
                    primary_mobile: { required: true },
                    contact_category_id: { required: true },
                    lifecycle_status: { required: true }
                },
                errorClass: 'error',
                errorElement: 'label'
            });

            if ($.fn.mask) {
                $('.mobile-mask').mask('0000-0000000');
            }

            if ($.fn.select2) {
                $('.select2-dept').select2({ placeholder: "Select Department", allowClear: true });
                $('.select2-cat').select2({ placeholder: "Select Category Group", allowClear: true });
            }
        });
    </script>
    @endpush
</x-admin-layout>
