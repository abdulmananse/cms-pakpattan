<div>
    {{-- @dd(auth()->user()) --}}
    <form id="filterForm" class="row text-end justify-content-xxl-end justify-content-center" >
        
        @if($category == 'true')
            <div class="col-xxl-{{ $col }} main-div col-md-3">
                <div class="label-float p-0">
                    {{ html()->select('category_id[]', getActiveCategories(), explode(',', request()->c))->class('form-select category_ids select2')->multiple() }}
                </div>
            </div>
        @endif
        
        @if($department == 'true')
            <div class="col-xxl-{{ $col }} main-div col-md-3">
                <div class="label-float p-0">
                    {{ html()->select('department_id[]', getActiveDepartments(), explode(',', request()->d))->class('form-select department_ids select2')->multiple() }}
                </div>
            </div>
        @endif
        
        @if($source == 'true')
        <div class="col-xxl-{{ $col }} main-div col-md-3">
            <div class="label-float p-0">
                {{ html()->select('source_id[]', getActiveSources(), explode(',', request()->s))->class('form-select source_ids select2')->multiple() }}
            </div>
        </div>
        @endif

        @if($status == 'true')
            <div class="col-xxl-{{ $col }} col-md-3 main-div">
                <div class="label-float p-0">
                    {{ html()->select('status[]', ['99' => 'Pending', '0' => 'Fresh', '4' => 'Overdue', '1' => 'Resolved', '3' => 'Reopen', '2' => 'Rejected'], explode(',', request()->status))->class('form-select status_ids select2')->multiple() }}
                </div>
            </div>
        @endif

        @if($date == 'true')
        <div class="col-xxl-{{ $col }} col-md-3 main-div">
            <div class="label-float p-0">
               <input type="text" id="daterange" name="date" class="form-control" value="{{ (request()->dt != 'all') ? request()->dt : '' }}" placeholder="Select Date"/>
            </div>
        </div>
        @endif

        <div class="col-md-1 main-div search_icon" style="">
            <button type="button" class="btn btn-continue btn-sm filterBtn"><i class="fas fa-search"></i></button>
        </div>
        
    </form>

    @push('scripts')
    <script>
        _$.ready(function() {

            $(".category_ids").select2({placeholder: "Select Categories"});  
            $(".department_ids").select2({placeholder: "Select Departments"});  
            $(".source_ids").select2({placeholder: "Select Sources"});  
            $(".status_ids").select2({placeholder: "Select Status"});  

            @if($date == 'true')
            let start = moment().subtract(6, 'days');
            let end = moment();
            let date = 'custom';

            @if($dateValue == 'all')
                start = moment('2025-01-01');
                end = moment();
            @endif

            @if(request()->filled('date'))
                const qDate = '{{ request()->date }}';
                if (qDate == 'all') {
                    date = 'all';
                } else {
                    const qDates = qDate.split(',');
                    start = moment(qDates[0]);
                    if (qDates.length > 1) {
                        end = moment(qDates[1]);
                    }
                }
            @endif

            const cb = (fromDate, endDate) => {
                start = fromDate;
                end = endDate;
                date = 'custom';

                $(".filterBtn").css("color", "#be1616");
            }

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                maxDate: moment(),
                locale: {
                    format: 'MMM D'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'All Days' : [moment('2025-01-01'), moment()]
                }
            }, cb);

            // cb(start, end);
            @endif

            _$.on("click", ".filterBtn", function(e) {
                e.preventDefault();
                $(".filterBtn").css("color", "#fff");
                loadingOverlay($('.filterBtn'))
                
                @if($category == 'true')
                let categoryIds = $('.category_ids').val();
                if (categoryIds == '' || categoryIds == undefined) {
                    categoryIds = ($('.category_ids').data('selectedid') > 0) ? $('.category_ids').data('selectedid') : [];
                }
                insertParam('c', categoryIds.join(","));
                @endif
                
                @if($department == 'true')
                let departmentIds = $('.department_ids').val();
                if (departmentIds == '' || departmentIds == undefined) {
                    departmentIds = ($('.department_ids').data('selectedid') > 0) ? $('.department_ids').data('selectedid') : [];
                }
                insertParam('d', departmentIds.join(","));
                @endif

                @if($source == 'true')
                let sourceIds = $('.source_ids').val();
                if (sourceIds == '' || sourceIds == undefined) {
                    sourceIds = ($('.source_ids').data('selectedid') > 0) ? $('.source_ids').data('selectedid') : [];
                }
                insertParam('s', sourceIds.join(","));
                @endif
                
                @if($status == 'true')
                let statusIds = $('.status_ids').val();
                if (statusIds == '' || statusIds == undefined) {
                    statusIds = ($('.status_ids').data('selectedid') > 0) ? $('.status_ids').data('selectedid') : [];
                }
                insertParam('status', statusIds);
                @endif
               
                @if($date == 'true')
                    if(date=='all') {
                        insertParam('date', 'all');
                    } else {
                        insertParam('date', start.format('YYYY-MM-DD') + ',' + end.format('YYYY-MM-DD'));
                    }
                @endif

                @if($refresh == 'true')
                location.reload();
                @else
                filterCallbackFun();
                @endif
            });

            @if($refresh == 'true')
            @else
            setTimeout(function() {
                $(".filterBtn").click();
            }, 3000);
            @endif
        });
    </script>
    @endpush
</div>
