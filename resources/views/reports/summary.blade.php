<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Summary" />
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->

            <div class="col-lg-12 col-md-12 ms-auto mb-3">
                <div class="card-body-dd">
                    <x-filter date=true category=true status=true refresh=true dateValue=all col=2 />
                </div>
            </div>

            <div class="row">
                <!-- product profit end -->

                <div class="col-xl-12 col-md-12">
                    <div class="card user-profile-list">
                        <div class="card-body-dd">
                            <div class="dt-responsive table-responsive">
                                <table class="table nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            @foreach($sources as $sourceId => $sourceName)
                                                @if($sourceIds->contains($sourceId))
                                                    <th>{{ $sourceName }}</th>
                                                @endif
                                            @endforeach
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $deptID => $deptName)
                                            @if($departmentIds->contains($deptID))
                                            <tr>
                                                <td class="text-start fw-bold">{{ $deptName }}</td>
                                                @php $rowTotal = $count = 0; @endphp
                                                @foreach($sources as $sourceId => $sourceName)
                                                    @if($sourceIds->contains($sourceId))
                                                    @php
                                                        if(isset($data[$deptID])) {
                                                            $count = $data[$deptID]->firstWhere('source_id', $sourceId)->total ?? 0;
                                                            $rowTotal += $count;
                                                        }
                                                    @endphp
                                                    <td>
                                                        <a href="{{ route('complaints.index', ['d' => $deptID, 's' => $sourceId, 'c' => (request()->filled('c') ? request()->c : 0), 'status' => (request()->status > 0 ? request()->status : ''), 'date' => (request()->filled('date') ? request()->date : 'all')]) }}" target="_blank">
                                                        {{ number_format($count) }}
                                                        </a>
                                                    </td>
                                                    @endif
                                                @endforeach
                                                <td class="fw-bold">
                                                    <a href="{{ route('complaints.index', ['d' => $deptID, 'c' => (request()->filled('c') ? request()->c : 0), 'status' => (request()->status > 0 ? request()->status : ''), 'date' => (request()->filled('date') ? request()->date : 'all')]) }}" target="_blank">
                                                        {{ number_format($rowTotal) }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            @foreach($sources as $sourceId => $sourceName)
                                                @if($sourceIds->contains($sourceId))
                                                @php
                                                    $colTotal = collect($departments)->map(function($name, $id) use($data, $sourceId) {
                                                        return isset($data[$id]) ? ($data[$id]->firstWhere('source_id', $sourceId)->total ?? 0): 0;
                                                    })->sum();
                                                    
                                                @endphp
                                                <td>
                                                    <a href="{{ route('complaints.index', ['s' => $sourceId, 'c' => (request()->filled('c') ? request()->c : 0), 'status' => (request()->status > 0 ? request()->status : ''), 'date' => (request()->filled('date') ? request()->date : 'all')]) }}" target="_blank">
                                                        {{ number_format($colTotal) }}
                                                    </a>
                                                </td>
                                                @endif
                                            @endforeach
                                            
                                            @php
                                                $grandTotal = $departments->map(function($departmentName, $departmentId) use($sources, $data) {
                                                    return $sources->map(function($sourceName, $sourceId) use ($data, $departmentId) {
                                                        return isset($data[$departmentId]) ? ($data[$departmentId]->firstWhere('source_id', $sourceId)->total ?? 0) : 0;
                                                    })->sum();
                                                })->sum();
                                            @endphp

                                            <td>
                                                <a href="{{ route('complaints.index', ['c' => (request()->filled('c') ? request()->c : 0), 'status' => (request()->status > 0 ? request()->status : ''), 'date' => (request()->filled('date') ? request()->date : 'all')]) }}" target="_blank">
                                                    {{ number_format($grandTotal) }}
                                                </a>
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
