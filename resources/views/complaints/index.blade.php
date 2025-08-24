<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Complaints" :button="['name' => 'Register Complaint', 'allow' => true, 'link' => route('complaints.create')]" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            
            <div class="col-lg-12 col-md-12 ms-auto mb-3">
                <div class="card-body-dd">
                    <x-filter date=true department=true source=true status=true col=2 />
                </div>
            </div>
            
            <div class="row">
                <!-- product profit end -->

                @if(Session::has('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <div>{{ Session::get('success') }}</div>
                </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <div>{{ Session::get('error') }}</div>
                </div>
                @endif

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
            let t;
            const columns = [{
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
                    data: 'source.name'
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

        /**
         * Filter Callback
         */
        const filterCallbackFun = () => {
            const url = route('complaints.datatable', getUrlParams(location.search));
            if (t != undefined) {
                t.ajax.url(url).load()
            } else {
                t = create_datatables(url, columns) 
            }
            stopOverlay($('.filterBtn'));
        }
        </script>
    @endpush
</x-admin-layout>
