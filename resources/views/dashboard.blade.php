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

                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-blue order-card">
                        <a class="card-body" href="{{ route('complaints.index') }}">
                            <h4 class="text-white">Total</h4>
                            <h2 class="text-end text-white"><i class="fa fa-exclamation-triangle float-start"></i><span>{{ number_format($summary->total) }}</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-green order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['status' => 1]) }}">
                            <h4 class="text-white">Fresh</h4>
                            <h2 class="text-end text-white"><i class="fa fa-bullhorn float-start"></i><span>{{ number_format($summary->fresh) }}</span>
                            </h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-red order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['status' => 2]) }}">
                            <h4 class="text-white">Overdue</h4>
                            <h2 class="text-end text-white"><i class="fas fa-clock float-start"></i><span>{{ number_format($summary->overdue) }}</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-green order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['status' => 1]) }}">
                            <h4 class="text-white">Resolved</h4>
                            <h2 class="text-end text-white"><i class="fa fa-check-circle float-start"></i><span>{{ number_format($summary->resolved) }}</span>
                            </h2>
                        </a>
                    </div>
                </div>
               
                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-yellow order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['status' => 0]) }}">
                            <h4 class="text-white">Pending</h4>
                            <h2 class="text-end text-white"><i class="fas fa-clock float-start"></i><span>{{ number_format($summary->pending) }}</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="card bg-c-red order-card">
                        <a class="card-body" href="{{ route('complaints.index', ['status' => 2]) }}">
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

                @can('Department Complaint Charts')
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Pending Complaints</h4>
                            <p  class="text-center"> by Department</p>
                            <div class="pie-chart-div" id="PendingComplaintsByDepartmentPieChart"></div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('Source Complaint Charts')
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center fw-bold">Pending Complaints</h4>
                            <p  class="text-center"> by Source</p>
                            <div class="pie-chart-div" id="PendingComplaintsBySourcePieChart"></div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('Department Complaint Charts')
                <div class="col-md-12 mb-3">
                    <div class="card card-h-100 w-100" style="padding-top: 0;">
                        <div class="card-body" style="padding-bottom: 0;">
                            <div class="row justify-content-between">
                                <div class="bg-white text-center rounded-lg p-4">
                                    <h4 class="text-center fw-bold">Complaint Status</h4>
                                    <p  class="text-center">by Departments</p>
                                    <div class="pie-chart-div" id="ComplaintsByDepartmentColumnChart"></div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
                
                @can('Source Complaint Charts')
                <div class="col-md-12 mb-3">
                    <div class="card card-h-100 w-100" style="padding-top: 0;">
                        <div class="card-body" style="padding-bottom: 0;">
                            <div class="row justify-content-between">
                                <div class="bg-white text-center rounded-lg p-4">
                                    <h4 class="text-center fw-bold">Complaint Status</h4>
                                    <p  class="text-center">by Source</p>
                                    <div class="pie-chart-div" id="ComplaintsBySourceColumnChart"></div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
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
        
        @can('Department Complaint Charts')
            Highcharts.chart('PendingComplaintsByDepartmentPieChart', {
                chart: chartOption,
                title: {text: ''},
                tooltip: tooltip,
                plotOptions: plotOptions,
                legend: {
                    align: 'right',       // Move legend to the right
                    verticalAlign: 'middle', // Center it vertically
                    layout: 'vertical'    // Stack items vertically
                },
                series: [{
                    name: 'Counts',
                    colorByPoint: true,
                    data: [
                        @foreach ($departments as $department)
                            @if($department->pending_complaints_count > 0)
                            {
                                name: '{{ $department->name }}',
                                y: {{ (int) $department->pending_complaints_count }}
                            },
                            @endif
                        @endforeach
                        ]
                }]
            });

            Highcharts.chart('ComplaintsByDepartmentColumnChart', {
                chart: {type: 'column',events: {load: function() {$('.highcharts-credits').hide();}}},
                title: {text: ''},
                xAxis: {
                    categories: [
                        @foreach ($departments as $department)
                        '{{ $department->name }}', 
                        @endforeach
                    ],
                    crosshair: true,
                    accessibility: {
                        description: 'Departments'
                    }
                },
                yAxis: {min: 0,title: {text: '(#)'}},
                tooltip: {valueSuffix: ' '},
                series: [
                    {
                        name: 'Resolved',
                        data: [
                            @foreach ($departments as $department)
                            {
                                y: {{ $department->resolved_complaints_count }},
                                department_id: {{ $department->id }}
                            },
                            @endforeach
                        ],
                        color: '#107b02',
                    },
                    {
                        name: 'Pending',
                        data: [
                            @foreach ($departments as $department)
                            {
                                y: {{ $department->pending_complaints_count }},
                                department_id: {{ $department->id }}
                            },
                            @endforeach
                        ],
                        color: '#be091f'
                    }
                ],
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    let department = this.department_id;   
                                    let status = this.series.name == 'Resolved' ? 1 : 0;    
                                    
                                    window.open(route('complaints.index', { d: department, status: status }), '_blank');
                                }
                            }
                        }
                    }
                },

            });
        @endcan
        
        @can('Source Complaint Charts')
            Highcharts.chart('PendingComplaintsBySourcePieChart', {
                chart: chartOption,
                title: {text: ''},
                tooltip: tooltip,
                plotOptions: plotOptions,
                legend: {
                    align: 'right',       // Move legend to the right
                    verticalAlign: 'middle', // Center it vertically
                    layout: 'vertical'    // Stack items vertically
                },
                series: [{
                    name: 'Counts',
                    colorByPoint: true,
                    data: [
                        @foreach ($sources as $source)
                            @if($source->pending_complaints_count > 0)
                            {
                                name: '{{ $source->name }}',
                                y: {{ (int) $source->pending_complaints_count }}
                            },
                            @endif
                        @endforeach
                        ]
                }]
            });

            Highcharts.chart('ComplaintsBySourceColumnChart', {
                chart: {type: 'column',events: {load: function() {$('.highcharts-credits').hide();}}},
                title: {text: ''},
                xAxis: {
                    categories: [
                        @foreach ($sources as $source)
                        '{{ $source->name }}', 
                        @endforeach
                    ],
                    crosshair: true,
                    accessibility: {
                        description: 'Sources'
                    }
                },
                yAxis: {min: 0,title: {text: '(#)'}},
                tooltip: {valueSuffix: ' '},
                series: [
                    {
                        name: 'Resolved',
                        data: [
                            @foreach ($sources as $source)
                            {
                                y: {{ $source->resolved_complaints_count }},
                                source_id: {{ $source->id }}
                            },
                            @endforeach
                        ],
                        color: '#107b02',
                    },
                    {
                        name: 'Pending',
                        data: [
                            @foreach ($sources as $source)
                            {
                                y: {{ $source->pending_complaints_count }},
                                source_id: {{ $source->id }}
                            },
                            @endforeach
                        ],
                        color: '#be091f'
                    }
                ],
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    let source = this.source_id;   
                                    let status = this.series.name == 'Resolved' ? 1 : 0;    
                                    
                                    window.open(route('complaints.index', { s: source, status: status }), '_blank');
                                }
                            }
                        }
                    }
                },

            });
        @endcan
    </script>
    @endpush
</x-admin-layout>
