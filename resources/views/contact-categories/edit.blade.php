<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Edit Contact Category" />
                    <!-- [ breadcrumb ] end -->

                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card card-custom gutter-b example example-compact">
                                        {{ html()->modelForm($contactCategory, 'PUT', route('contact-categories.update', $contactCategory->uuid))->id('formValidation')->open() }}
                                            <div class="card-body row">
                                                @include('contact-categories.form')
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary mr-2">Update Category</button>
                                                <a href="{{ route('contact-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('#formValidation').validate();
        });
    </script>
    @endpush
</x-admin-layout>
