<div>
    {{-- @dd(auth()->user()) --}}
    <form id="filterForm" class="row text-end justify-content-xxl-end justify-content-center" >
        
        @if($category == 'true')
            <div class="col-xxl-{{ $col }} main-div col-md-3">
                <div class="label-float p-0">
                    {{ html()->select('category_id', getActiveCategories(), request()->c)->class('form-select')->placeholder('All Categories') }}
                </div>
            </div>
        @endif
        
        @if($department == 'true')
            <div class="col-xxl-{{ $col }} main-div col-md-3">
                <div class="label-float p-0">
                    {{ html()->select('department_id', getActiveDepartments(), request()->d)->class('form-select')->placeholder('All Departments') }}
                </div>
            </div>
        @endif
        
        @if($source == 'true')
        <div class="col-xxl-{{ $col }} main-div col-md-3">
            <div class="label-float p-0">
                {{ html()->select('source_id', getActiveSources(), request()->s)->class('form-select')->placeholder('All Sources') }}
            </div>
        </div>
        @endif

        @if($status == 'true')
            <div class="col-xxl-{{ $col }} col-md-3 main-div">
                <div class="label-float p-0">
                    {{ html()->select('status', ['' => 'Complaint Status', '0' => 'Pending', '4' => 'Overdue', '1' => 'Resolved', '3' => 'Reopen', '2' => 'Rejected'], request()->status)->class('form-select') }}
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

            @if($date == 'true')
            let start = moment().subtract(6, 'days');
            let end = moment();
            let date = 'custom';

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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            // cb(start, end);
            @endif

            _$.on("click", ".filterBtn", function(e) {
                e.preventDefault();
                $(".filterBtn").css("color", "#fff");
                loadingOverlay($('.filterBtn'))
                
                
                @if($category == 'true')
                let categoryId = $('[name=category_id]').val();
                if (categoryId == '' || categoryId == undefined) {
                    categoryId = ($('[name=category_id]').data('selectedid') > 0) ? $('[name=category_id]').data('selectedid') : 0;
                }
                insertParam('c', categoryId);
                @endif
                
                @if($department == 'true')
                let departmentId = $('[name=department_id]').val();
                if (departmentId == '' || departmentId == undefined) {
                    departmentId = ($('[name=department_id]').data('selectedid') > 0) ? $('[name=department_id]').data('selectedid') : 0;
                }
                insertParam('d', departmentId);
                @endif

                @if($source == 'true')
                let sourceId = $('[name=source_id]').val();
                if (sourceId == '' || sourceId == undefined) {
                    sourceId = ($('[name=source_id]').data('selectedid') > 0) ? $('[name=source_id]').data('selectedid') : 0;
                }
                insertParam('s', sourceId);
                @endif
                
                @if($status == 'true')
                let status = $('[name=status]').val();
                if (status == '' || status == undefined) {
                    status = ($('[name=status]').data('selectedid') > 0) ? $('[name=status]').data('selectedid') : '';
                }
                insertParam('status', status);
                @endif
               
                @if($date == 'true')
                    if(date=='all') {
                        insertParam('date', 'all');
                    } else {
                        insertParam('date', start.format('YYYY-MM-DD') + ',' + end.format('YYYY-MM-DD'));
                    }
                @endif

                @if($refresh == 'true')
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
