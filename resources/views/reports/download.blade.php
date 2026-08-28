<x-admin-layout>
    @push('styles')
        <style>
            .download-card {
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                border: 1px solid #eef2f5;
                margin-bottom: 24px;
            }
            .section-header {
                font-weight: 600;
                font-size: 1.1rem;
                color: #2c3e50;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
                border-bottom: 2px solid #f1f5f9;
                padding-bottom: 10px;
            }
            .column-checkbox-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 14px 20px;
                padding: 10px 4px;
            }
            .custom-checkbox-card {
                display: flex;
                align-items: center;
                padding: 10px 14px;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                transition: all 0.2s ease-in-out;
                cursor: pointer;
                user-select: none;
            }
            .custom-checkbox-card:hover {
                background-color: #edf2f7;
                border-color: #cbd5e1;
            }
            .custom-checkbox-card input[type="checkbox"] {
                width: 18px;
                height: 18px;
                margin-right: 10px;
                cursor: pointer;
                accent-color: #4680ff;
            }
            .custom-checkbox-card label {
                margin-bottom: 0;
                cursor: pointer;
                font-weight: 500;
                color: #334155;
                font-size: 0.92rem;
            }
            .select-all-container {
                background-color: #eff6ff;
                border: 1px solid #bfdbfe;
                border-radius: 6px;
                padding: 10px 16px;
                margin-bottom: 16px;
                display: inline-flex;
                align-items: center;
            }
            .select-all-container label {
                font-weight: 600;
                color: #1e40af;
                margin-bottom: 0;
                margin-left: 8px;
                cursor: pointer;
            }
            .btn-download-export {
                min-width: 160px;
                padding: 10px 24px;
                font-weight: 600;
                font-size: 1rem;
                border-radius: 6px;
                box-shadow: 0 4px 6px -1px rgba(70, 128, 255, 0.4);
                transition: all 0.2s ease;
            }
            .btn-download-export:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 12px -2px rgba(70, 128, 255, 0.5);
            }
            .validation-alert {
                display: none;
                margin-bottom: 16px;
            }
        </style>
    @endpush

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Complaints Download" />
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-xl-12 col-md-12">

                    <!-- Server Alerts -->
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="feather icon-check-circle me-2"></i> {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="feather icon-alert-circle me-2"></i> {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Client Validation Alert -->
                    <div class="alert alert-warning alert-dismissible fade show validation-alert" id="clientValidationAlert" role="alert">
                        <i class="feather icon-alert-triangle me-2"></i> <span id="validationAlertText"></span>
                        <button type="button" class="btn-close" onclick="$('#clientValidationAlert').hide()"></button>
                    </div>

                    <form id="complaintDownloadForm" action="{{ route('reports.download.export') }}" method="POST">
                        @csrf

                        <!-- Card 1: Column Selection -->
                        <div class="card download-card">
                            <div class="card-body">
                                <div class="section-header">
                                    <i class="feather icon-check-square text-primary"></i>
                                    <span>Select Column</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                    <div class="select-all-container">
                                        <input class="form-check-input" type="checkbox" id="selectAllColumns" checked>
                                        <label for="selectAllColumns">Select All Columns</label>
                                    </div>
                                    <small class="text-muted"><span id="selectedCount">{{ count($columns) }}</span> of {{ count($columns) }} columns selected</small>
                                </div>

                                <div class="column-checkbox-grid">
                                    @foreach ($columns as $key => $colData)
                                        <div class="custom-checkbox-card">
                                            <input class="column-checkbox" type="checkbox" name="columns[]" value="{{ $key }}" id="col_{{ $key }}" checked>
                                            <label for="col_{{ $key }}">{{ $colData['label'] }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Date Filter -->
                        <div class="card download-card">
                            <div class="card-body">
                                <div class="section-header">
                                    <i class="feather icon-calendar text-primary"></i>
                                    <span>Date Range Filter (Creation Date)</span>
                                </div>

                                <div class="row g-3 align-items-center">
                                    <div class="col-md-5">
                                        <label for="from_date" class="form-label fw-bold">From Date</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control date-picker-input" value="{{ old('from_date') }}" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="to_date" class="form-label fw-bold">To Date</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control date-picker-input" value="{{ old('to_date') }}" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end  mt-5">
                                        <button type="button" class="btn btn-outline-secondary w-100" id="btnClearDates" title="Clear Date Filter">
                                            <i class="feather icon-x-circle me-1"></i> Clear Dates
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">Leaving date fields blank will export all available complaints according to your user role permissions.</small>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="d-flex justify-content-end mb-4">
                            <button type="submit" class="btn btn-primary btn-download-export" id="btnDownload">
                                <i class="feather icon-download me-2" id="downloadBtnIcon"></i>
                                <span id="downloadBtnText">Download Report</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const totalColumns = $('.column-checkbox').length;

                function updateSelectedCount() {
                    const checkedCount = $('.column-checkbox:checked').length;
                    $('#selectedCount').text(checkedCount);
                    $('#selectAllColumns').prop('checked', checkedCount === totalColumns);
                    $('#selectAllColumns').prop('indeterminate', checkedCount > 0 && checkedCount < totalColumns);
                }

                // Toggle Select All
                $('#selectAllColumns').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('.column-checkbox').prop('checked', isChecked);
                    updateSelectedCount();
                });

                // Individual Checkbox Change
                $('.column-checkbox').on('change', function() {
                    updateSelectedCount();
                    $('#clientValidationAlert').hide();
                });

                // Clear Dates Button
                $('#btnClearDates').on('click', function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#clientValidationAlert').hide();
                });

                // Form Validation & Submission Handling
                $('#complaintDownloadForm').on('submit', function(e) {
                    const checkedCount = $('.column-checkbox:checked').length;
                    const fromDateVal = $('#from_date').val();
                    const toDateVal = $('#to_date').val();

                    $('#clientValidationAlert').hide();

                    // Validation 1: At least one column must be selected
                    if (checkedCount === 0) {
                        e.preventDefault();
                        $('#validationAlertText').text('Please select at least one column to include in the downloaded report.');
                        $('#clientValidationAlert').fadeIn();
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                        return false;
                    }

                    // Validation 2: From Date must not be greater than To Date
                    if (fromDateVal && toDateVal && fromDateVal > toDateVal) {
                        e.preventDefault();
                        $('#validationAlertText').text('The From Date cannot be greater than the To Date.');
                        $('#clientValidationAlert').fadeIn();
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                        return false;
                    }

                    // Show Loading State during download request
                    const $btn = $('#btnDownload');
                    const $icon = $('#downloadBtnIcon');
                    const $text = $('#downloadBtnText');

                    $btn.prop('disabled', true);
                    $icon.removeClass('icon-download').addClass('icon-loader spin');
                    $text.text('Generating Report...');

                    // Re-enable button after 4 seconds
                    setTimeout(function() {
                        $btn.prop('disabled', false);
                        $icon.removeClass('icon-loader spin').addClass('icon-download');
                        $text.text('Download Report');
                    }, 4000);
                });
            });
        </script>
    @endpush
</x-admin-layout>
