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
                        <div class="card-body">
                            <h4 class="text-white">Total Licenses</h4>
                            <h2 class="text-end text-white"><i class="feather icon-users float-start"></i><span>{{ number_format(1111) }}</span></h2>
                            {{-- <p class="m-b-0">Completed Orders<span class="float-end">351</span></p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-green order-card">
                        <div class="card-body">
                            <h4 class="text-white">Total Schools</h4>
                            <h2 class="text-end text-white"><i class="feather icon-home float-start"></i><span>{{ number_format(22222) }}</span>
                            </h2>
                        </div>
                    </div>
                </div>
               
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-yellow order-card">
                        <div class="card-body">
                            <h4 class="text-white">Total Teachers</h4>
                            <h2 class="text-end text-white"><i
                                    class="fas fa-user-tie float-start"></i><span>{{ number_format(33333) }}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-blue order-card">
                        <div class="card-body">
                            <h4 class="text-white">Total Students</h4>
                            <h2 class="text-end text-white"><i
                                    class="feather icon-users float-start"></i><span>{{ number_format(4444) }}</span></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-blue order-card">
                        <div class="card-body">
                            <h4 class="text-white">Male Students</h4>
                            <h2 class="text-end text-white"><i class="feather icon-users float-start"></i><span>55555</span></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-c-green order-card">
                        <div class="card-body">
                            <h4 class="text-white">Female Students</h4>
                            <h2 class="text-end text-white"><i class="feather icon-users float-start"></i><span>66666</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Schools</h4>
                            <p  class="text-center"> by Level</p>
                            <div class="pie-chart-div" id="levelWiseSchoolPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Schools</h4>
                            <p  class="text-center"> by Status</p>
                            <div class="pie-chart-div" id="statusWiseSchoolPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Teachers</h4>
                            <p  class="text-center"> by Gender</p>
                            <div class="pie-chart-div" id="genderWiseTeacherPieChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
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

        Highcharts.chart('levelWiseSchoolPieChart', {
            chart: chartOption,
            title: {text: ''},
            tooltip: tooltip,
            plotOptions: plotOptions,
            series: [{
                name: 'Counts',
                colorByPoint: true,
                data: [{
                        name: 'Primary Schools',
                        y: 1111,
                    }, {
                        name: 'Class 6 Schools',
                        y: 2222,
                    }, {
                        name: 'Class 7 Schools',
                        y: 3333,
                    }, {
                        name: 'Elementary Schools',
                        y: 123,
                    },{
                        name: 'High Schools',
                        y: 123,
                    }, {
                        name: 'High. Sec Schools',
                        y: 321,
                    },]
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
