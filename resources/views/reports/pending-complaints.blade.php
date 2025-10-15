<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Pending Complaints" />
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
                                            @foreach($departments as $deptID => $deptName)
                                                @if($departmentIds->contains($deptID))
                                                    <th>{{ $deptName }}</th>
                                                @endif
                                            @endforeach
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sources as $sourceId => $sourceName)
                                            <tr>
                                                <td class="text-start fw-bold">{{ $sourceName }}</td>
                                                @php $rowTotal = $count = 0; @endphp
                                                @foreach($departments as $deptID => $deptName)
                                                    @if($departmentIds->contains($deptID))
                                                    @php
                                                        if(isset($data[$sourceId])) {
                                                            $count = $data[$sourceId]->firstWhere('department_id', $deptID)->total ?? 0;
                                                            $rowTotal += $count;
                                                        }
                                                    @endphp
                                                    <td>{{ $count }}</td>
                                                    @endif
                                                @endforeach
                                                <td class="fw-bold">{{ $rowTotal }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            @foreach($departments as $deptID => $deptName)
                                                @if($departmentIds->contains($deptID))
                                                @php
                                                    $colTotal = collect($sources)->map(function($name, $id) use($data, $deptID) {
                                                        return isset($data[$id]) ? ($data[$id]->firstWhere('department_id', $deptID)->total ?? 0): 0;
                                                    })->sum();
                                                @endphp
                                                <td>{{ $colTotal }}</td>
                                                @endif
                                            @endforeach
                                            
                                            @php
                                                $grandTotal = $sources->map(function($sourceName, $sourceId) use($departments, $data) {
                                                    return $departments->map(function($deptName, $deptId) use ($data, $sourceId) {
                                                        return isset($data[$sourceId]) ? ($data[$sourceId]->firstWhere('department_id', $deptId)->total ?? 0) : 0;
                                                    })->sum();
                                                })->sum();
                                            @endphp

                                            <td>{{ $grandTotal }}</td>
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
