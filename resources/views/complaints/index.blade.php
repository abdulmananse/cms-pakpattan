<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            {{-- <x-breadcrumb title="Complaints" :button="['name' => 'Add', 'allow' => true, 'link' => route('complaints.create')]" /> --}}
            <x-breadcrumb title="Complaints" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- product profit end -->

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd">
                            <x-table :keys="['Complaint No', 'Name', 'CNIC', 'Mobile', 'Category', 'Department', 'Complaint By', 'Source', 'Status', '']"></x-table>
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

                const datatable_url = route('complaints.datatable');
                const datatable_columns = [{
                        data: 'complaint_no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'cnic'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'category.name'
                    },
                    {
                        data: 'department.name'
                    },
                    {
                        data: 'complaint_by.name'
                    },
                    {
                        data: 'source'
                    },
                    {
                        data: 'complaint_status',
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
