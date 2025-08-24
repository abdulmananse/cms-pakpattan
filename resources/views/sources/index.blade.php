<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Sources" :button="['name' => 'Add', 'allow' => true, 'link' => route('sources.create')]" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- product profit end -->

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd">
                            <x-table :keys="['Name', 'Ordering', 'Status', '']"></x-table>
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

                const datatable_url = route('sources.datatable');
                const datatable_columns = [{
                        data: 'name'
                    },
                    {
                        data: 'ordering'
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
