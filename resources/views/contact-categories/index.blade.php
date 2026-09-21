<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Contact Categories" :button="['name' => 'Add Category', 'allow' => true, 'link' => route('contact-categories.create')]" />
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                @if(Session::has('success'))
                <div class="col-12">
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <div>{{ Session::get('success') }}</div>
                    </div>
                </div>
                @endif
                @if(Session::has('error'))
                <div class="col-12">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <div>{{ Session::get('error') }}</div>
                    </div>
                </div>
                @endif

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd">
                            <x-table :keys="['Name', 'Description', 'Assigned Officers', 'Status', 'Actions']"></x-table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @include('layouts.dataTablesFiles')
    @push('scripts')
        <script>
            $(document).ready(function() {
                const datatable_url = route('contact-categories.datatable');
                const datatable_columns = [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'officers_count',
                        name: 'officers_count',
                        width: '15%',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        width: '10%',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '15%',
                        orderable: false,
                        searchable: false
                    }
                ];

                create_datatables(datatable_url, datatable_columns);
            });
        </script>
    @endpush
</x-admin-layout>
