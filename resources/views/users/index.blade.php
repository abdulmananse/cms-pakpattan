<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Users" :button="['name' => 'Add', 'allow' => true, 'link' => route('users.create')]" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- product profit end -->

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd theme-tbl">
                            <x-table :keys="['Name', 'Username', 'Mobile', 'Email', 'Role', 'Status', '']"></x-table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('layouts.dataTablesFiles')
    @push('scripts')
        <script>
            $(document).ready(function() {
                const datatable_url = route('users.datatable');
                const datatable_columns = [{
                        data: 'name'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'role',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'is_active',
                        width: '5%',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        width: '10%',
                        orderable: false,
                        searchable: false
                    }
                ];

                create_datatables(datatable_url, datatable_columns);
            });
        </script>
    @endpush
</x-admin-layout>
