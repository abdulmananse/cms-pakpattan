<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Resolved Complaints" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- product profit end -->

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd">
                            <div class="dt-responsive table-responsive">
                                <table class="table nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>Source</th>
                                            @foreach($departments as $dept)
                                                <th>{{ $dept->name }}</th>
                                            @endforeach
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sources as $src)
                                            <tr>
                                                <td class="text-start fw-bold">{{ $src }}</td>
                                                @php $rowTotal = $count = 0; @endphp
                                                @foreach($departments as $dept)
                                                    @php
                                                        if(isset($data[$src])) {
                                                            $count = $data[$src]->firstWhere('department_id', $dept->id)->total ?? 0;
                                                            $rowTotal += $count;
                                                        }
                                                    @endphp
                                                    <td>{{ $count }}</td>
                                                @endforeach
                                                <td class="fw-bold">{{ $rowTotal }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            @foreach($departments as $dept)
                                                @php
                                                    $colTotal = collect($sources)->sum(function($src) use($data, $dept) {
                                                        return (isset($data[$src])) ? ($data[$src]->firstWhere('department_id', $dept->id)->total ?? 0) : 0;
                                                    });
                                                @endphp
                                                <td>{{ $colTotal }}</td>
                                            @endforeach
                                            <td>
                                                {{ collect($sources)->sum(function($src) use($departments, $data) {
                                                    return $departments->sum(function($dept) use($data, $src) {
                                                        return (isset($data[$src])) ? ($data[$src]->firstWhere('department_id', $dept->id)->total ?? 0) : 0;
                                                    });
                                                }) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
