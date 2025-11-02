<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Charts" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->

            <div class="col-lg-12 col-md-12 ms-auto mb-3">
                <div class="card-body-dd">
                    <x-filter date=true department=true source=true category=true status=true refresh=true dateValue=all col=2 />
                </div>
            </div>

            <div class="row">

                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card card-h-100 w-100" style="padding-top: 0;">
                        <div class="card-body" style="padding-bottom: 0;">
                            <div class="row justify-content-between">
                                <div class="bg-white text-center rounded-lg p-4">
                                    <h4 class="text-center fw-bold">Category Wise</h4>
                                    <p  class="text-center">{{ $subTitle }}</p>
                                    <div class="pie-chart-div" id="ComplaintsByCategoryBarChart"></div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card card-h-100 w-100" style="padding-top: 0;">
                        <div class="card-body" style="padding-bottom: 0;">
                            <div class="row justify-content-between">
                                <div class="bg-white text-center rounded-lg p-4">
                                    <h4 class="text-center fw-bold">Source Wise</h4>
                                    <p  class="text-center">{{ $subTitle }}</p>
                                    <div class="pie-chart-div" id="ComplaintsBySourcePieChart"></div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-h-100 w-100" style="padding-top: 0;">
                        <div class="card-body" style="padding-bottom: 0;">
                            <div class="row justify-content-between">
                                <div class="bg-white text-center rounded-lg p-4">
                                    <h4 class="text-center fw-bold">Status Wise</h4>
                                    <p  class="text-center">{{ $subTitle }}</p>
                                    <div class="pie-chart-div" id="ComplaintsByStatusColumnChart"></div>	
                                </div>
                            </div>
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

        Highcharts.chart('ComplaintsByCategoryBarChart', {
            chart: {
                type: 'bar',
                backgroundColor: '#333'
            },
            title: {
                text: 'Category Wise Complaints',
                style: { color: '#fff', fontSize: '20px', fontWeight: 'bold' }
            },
            xAxis: {
                categories: [
                    @foreach($categories as $category)
                        '{{ $category['name'] }}',
                    @endforeach
                ],
                // labels: { style: { color: '#fff', fontSize: '13px' } },
                // gridLineWidth: 0
            },
            yAxis: {
                title: { text: '' },
                gridLineColor: 'rgba(255,255,255,0.1)',
                labels: { style: { color: '#fff' } }
            },
            legend: { enabled: false },
            tooltip: {
                backgroundColor: '#222',
                style: { color: '#fff' },
                pointFormat: '<b>{point.y}</b>'
            },
            series: [{
                name: 'Complaints',
                data: [
                    @foreach($categories as $category)
                        { y: {{ $category['total'] }}, color: '{{ $category["color"] }}'  },
                    @endforeach
                ],
                dataLabels: {
                enabled: true,
                align: 'right',
                color: '#fff',
                style: { fontSize: '13px', fontWeight: 'bold' },
                format: '{y}'
                }
            }],
            credits: { enabled: false }
        });

        Highcharts.chart('ComplaintsBySourcePieChart', {
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
                name: 'Complaints',
                colorByPoint: true,
                data: [
                    @foreach ($sources as $source)
                    {
                        name: '{{ $source["name"] }}',
                        y: {{ (int) $source["total"] }}
                    },
                    @endforeach
                ]
            }]
        });

        Highcharts.chart('ComplaintsByStatusColumnChart', {
            chart: {type: 'column'},
            title: {text: ''},
            xAxis: {categories: ['Fresh', 'Overdue', 'Resolved', 'Reopen', 'Rejected']},
            yAxis: {title: { text: '(#)' }},
            legend: { enabled: false },
            series: [{
                name: 'Complaints',
                data: [
                    { y: {{ $summary->fresh }}, color: '#FFC107' }, // Fresh - yellow
                    { y: {{ $summary->overdue }}, color: '#F44336' }, // Overdue - red
                    { y: {{ $summary->resolved }}, color: '#4CAF50' }, // Resolved - green
                    { y: {{ $summary->reopen }}, color: '#FF9800' },   // Reopened - orange
                    { y: {{ $summary->rejected }}, color: '#8BC34A' }    // Rejected - light green
                ],
                dataLabels: {
                    enabled: true,
                    color: '#fff',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                }
            }],
            credits: { enabled: false }
        });
    </script>
    @endpush
</x-admin-layout>
