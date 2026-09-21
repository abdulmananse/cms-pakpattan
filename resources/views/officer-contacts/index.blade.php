<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Officer Contact Records" :button="['name' => 'Add Officer', 'allow' => true, 'link' => route('officer-contacts.create')]" />
            <!-- [ breadcrumb ] end -->

            <!-- [ Filters Section ] start -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body p-3">
                    <form id="officerFilterForm" class="row g-2 align-items-center">
                        <!-- Department Filter -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <label class="form-label small text-muted mb-1">Department</label>
                            <select id="filter_department" class="form-select select2-filter">
                                <option value="">All Departments</option>
                                @foreach($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label small text-muted mb-1">Category Group</label>
                            <select id="filter_category" class="form-select select2-filter">
                                <option value="">All Categories</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lifecycle Status Filter -->
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label small text-muted mb-1">Lifecycle Status</label>
                            <select id="filter_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- PCM Filter -->
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label small text-muted mb-1">PCM Magistrate</label>
                            <select id="filter_pcm" class="form-select">
                                <option value="all">All Officers</option>
                                <option value="1">PCM Only</option>
                                <option value="0">Non-PCM Only</option>
                            </select>
                        </div>

                        <!-- Favorite Filter -->
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label small text-muted mb-1">Favorite Flag</label>
                            <select id="filter_favorite" class="form-select">
                                <option value="all">All Records</option>
                                <option value="1">&#9733; Favorites Only</option>
                                <option value="0">Standard Only</option>
                            </select>
                        </div>

                        <!-- Reset / Apply Buttons -->
                        <div class="col-xl-1 col-md-4 col-sm-6 text-end pt-4">
                            <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100" title="Reset Filters">
                                <i class="feather icon-refresh-cw"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- [ Filters Section ] end -->

            <!-- [ Main Table ] start -->
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
                    <div class="card user-profile-list shadow-sm">
                        <div class="card-body-dd">
                            <x-table :keys="['Photo', 'Officer Name', 'Designation', 'Department', 'Office / Establishment', 'Primary Mobile', 'Category', 'Lifecycle Status', 'PCM', 'Favorite', 'Actions']"></x-table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Table ] end -->
        </div>
    </div>

    @include('layouts.dataTablesFiles')
    @push('scripts')
        <script>
            $(document).ready(function() {
                if ($.fn.select2) {
                    $('.select2-filter').select2({ width: '100%' });
                }

                const datatable_url = route('officer-contacts.datatable');
                const datatable_columns = [
                    {
                        data: 'photo',
                        name: 'photo',
                        width: '5%',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'officer_name',
                        name: 'officer_name'
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'department',
                        name: 'department.name'
                    },
                    {
                        data: 'office_establishment',
                        name: 'office_establishment',
                        defaultContent: '<span class="text-muted">-</span>'
                    },
                    {
                        data: 'primary_mobile',
                        name: 'primary_mobile'
                    },
                    {
                        data: 'category',
                        name: 'contactCategory.name'
                    },
                    {
                        data: 'lifecycle_status',
                        name: 'lifecycle_status',
                        width: '8%',
                        className: 'text-center'
                    },
                    {
                        data: 'is_pcm',
                        name: 'is_pcm',
                        width: '8%',
                        className: 'text-center'
                    },
                    {
                        data: 'is_favorite',
                        name: 'is_favorite',
                        width: '5%',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '12%',
                        orderable: false,
                        searchable: false
                    }
                ];

                // Create custom Datatable instance with filter parameters passed
                let $table = $('.datatable');
                $table.find('thead tr').prepend("<th>#</th>");
                $table.find('tfoot tr').prepend("<th>#</th>");

                datatable_columns.unshift({
                    name: 'index',
                    data: 'index',
                    width: '2%',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                });

                let table = $table.DataTable({
                    oLanguage: { sProcessing: '<img src="' + Ziggy.url + '/images/bx_loader.gif">' },
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    pageLength: 10,
                    ajax: {
                        url: datatable_url,
                        type: 'POST',
                        data: function(d) {
                            d.department_id = $('#filter_department').val();
                            d.contact_category_id = $('#filter_category').val();
                            d.lifecycle_status = $('#filter_status').val();
                            d.is_pcm = $('#filter_pcm').val();
                            d.is_favorite = $('#filter_favorite').val();
                        }
                    },
                    columns: datatable_columns,
                    order: [[2, 'asc']],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var startIndex = api.context[0]._iDisplayStart;
                        api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                            cell.innerHTML = startIndex + i + 1;
                        });
                    }
                });

                // Filter triggers
                $('#filter_department, #filter_category, #filter_status, #filter_pcm, #filter_favorite').on('change', function() {
                    table.draw();
                });

                $('#btn-reset-filters').on('click', function() {
                    $('#filter_department').val('').trigger('change');
                    $('#filter_category').val('').trigger('change');
                    $('#filter_status').val('');
                    $('#filter_pcm').val('all');
                    $('#filter_favorite').val('all');
                    table.draw();
                });

                // Quick PCM Toggle Handler
                $table.on('click', '.btn-pcm-toggle', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $table.find("tbody").LoadingOverlay("show");
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            $table.find("tbody").LoadingOverlay("hide");
                            if (res.success) {
                                table.ajax.reload(null, false);
                                successMessage(res.message);
                            } else {
                                errorMessage(res.message);
                            }
                        },
                        error: function() {
                            $table.find("tbody").LoadingOverlay("hide");
                            errorMessage('Error toggling PCM status.');
                        }
                    });
                });

                // Quick Favorite Toggle Handler
                $table.on('click', '.btn-fav-toggle', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $table.find("tbody").LoadingOverlay("show");
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            $table.find("tbody").LoadingOverlay("hide");
                            if (res.success) {
                                table.ajax.reload(null, false);
                                successMessage(res.message);
                            } else {
                                errorMessage(res.message);
                            }
                        },
                        error: function() {
                            $table.find("tbody").LoadingOverlay("hide");
                            errorMessage('Error toggling Favorite status.');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>
