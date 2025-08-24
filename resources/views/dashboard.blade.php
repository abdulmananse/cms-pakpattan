<x-admin-layout>

    <style>
        g.highcharts-label,
        g.highcharts-legend-item {
            font-family: 'Poppins';
        }
    </style>

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Dashboard" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-blue order-card">
                        <a class="card-body" href="{{ route('complaints.index') }}">
                            <h4 class="text-white">Total Complaints</h4>
                            <h2 class="text-end text-white"><i class="fa fa-exclamation-triangle float-start"></i><span>{{ number_format($summary->total) }}</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-green order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['s' => 1]) }}">
                            <h4 class="text-white">Resolved</h4>
                            <h2 class="text-end text-white"><i class="fa fa-check-circle float-start"></i><span>{{ number_format($summary->resolved) }}</span>
                            </h2>
                        </a>
                    </div>
                </div>
               
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-yellow order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['s' => 0]) }}">
                            <h4 class="text-white">Pending</h4>
                            <h2 class="text-end text-white"><i class="fas fa-clock float-start"></i><span>{{ number_format($summary->pending) }}</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-red order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['s' => 2]) }}">
                            <h4 class="text-white">Rejected</h4>
                            <h2 class="text-end text-white"><i class="fas fa-times-circle float-start"></i><span>{{ number_format($summary->rejected) }}</span></h2>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Complaints</h4>
                            <p  class="text-center"> by Status</p>
                            <div class="pie-chart-div" id="ComplaintsPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12 d-none">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Schools</h4>
                            <p  class="text-center"> by Status</p>
                            <div class="pie-chart-div" id="statusWiseSchoolPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12 d-none">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Teachers</h4>
                            <p  class="text-center"> by Gender</p>
                            <div class="pie-chart-div" id="genderWiseTeacherPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12 d-none">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Students</h4>
                            <p  class="text-center"> by Gender</p>
                            <div class="pie-chart-div" id="genderWiseStudentPieChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('plugins/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('plugins/highcharts/exporting.js') }}"></script>
    <script>
        $(document).ready(function () {
            const alert_popup = "{{session('alert_popup')}}";
            if(alert_popup){
                $('#alertModal').modal('show');
            }
        });
    
        Highcharts.setOptions({
            lang: {
                thousandsSep: ','
            }
        });

        const chartOption = {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            events: {
                load: function() {
                    $('.highcharts-credits').hide();
                }
            }
        };

        const plotOptions = {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    alignTo: 'fixedOffset',
                    enabled: true,
                    format: '<b>{point.name}</b> <br/> {point.y:,.0f} <br/> {point.percentage:.1f} %'
                },
                showInLegend: true
            }
        };

        const tooltip = {
                pointFormat: '<b> {point.y:,.0f} | {point.percentage:.1f} %</b>'
            };

        Highcharts.chart('ComplaintsPieChart', {
            chart: chartOption,
            title: {text: ''},
            tooltip: tooltip,
            plotOptions: plotOptions,
            series: [{
                name: 'Counts',
                colorByPoint: true,
                data: [{
                        name: 'Pending',
                        y: {{ (int) $summary->pending }},
                        color: '#FFB64D'
                    }, {
                        name: 'Resolved',
                        y: {{ (int) $summary->resolved }},
                        color: '#2ed8b6'
                    }, {
                        name: 'Rejected',
                        y: {{ (int) $summary->rejected }},
                        color: '#FF5370'
                    }]
            }]
        });
        
        Highcharts.chart('statusWiseSchoolPieChart', {
            chart: chartOption,
            title: {text: ''},
            tooltip: tooltip,
            plotOptions: plotOptions,
            series: [{
                name: 'Counts',
                colorByPoint: true,
                data: [{
                        name: 'Functional',
                        y: 21312,
                        color: 'rgb(91, 137, 210)'
                    }, {
                        name: 'Non-Functional',
                        y: 123,
                        color: 'rgb(212, 76, 157)'
                    },]
            }]
        });
        
        Highcharts.chart('genderWiseTeacherPieChart', {
            chart: chartOption,
            title: {text: ''},
            tooltip: tooltip,
            plotOptions: plotOptions,
            series: [{
                name: 'Counts',
                colorByPoint: true,
                data: [{
                        name: 'Male',
                        y: 312,
                        color: 'rgb(91, 137, 210)'
                    }, {
                        name: 'Female',
                        y: 12,
                        color: 'rgb(212, 76, 157)'
                    },]
            }]
        });

        Highcharts.chart('genderWiseStudentPieChart', {
            chart: chartOption,
            title: {text: ''},
            tooltip: tooltip,
            plotOptions: plotOptions,
            series: [{
                name: 'Counts',
                colorByPoint: true,
                data: [{
                        name: 'Male',
                        y: 123,
                        color: 'rgb(91, 137, 210)'
                    }, {
                        name: 'Female',
                        y: 12,
                        color: 'rgb(212, 76, 157)'
                    },]
            }]
        });
    </script>
    @endpush
</x-admin-layout>
