<x-admin-layout>

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            padding: 15px 15px;
            background: #399268;
            color: white;
            border-radius: 15px 15px 0 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .table th {
            width: 200px;
            background-color: #f1f3f5;
        }
        .attachments img {
            width: 150px;
            height: auto;
            border-radius: 10px;
            margin: 5px;
            border: 1px solid #dee2e6;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .attachments img:hover {
            transform: scale(1.05);
        }
    </style>

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Complaint Details" :breadcrumbs="[
                        ['name' => 'Complaints', 'allow' => true, 'link' => route('complaints.index')],
                        ['name' => 'Complaint Details', 'allow' => true, 'link' => '#'],
                    ]" />
                    <!-- [ breadcrumb ] end -->
                    
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ basic-table ] start -->
                                <div class="col-xl-12">

                                    <div class="card">
                                        <div class="card-header text-center">
                                            Complaint Details
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered align-middle">
                                            <tbody>
                                                <tr>
                                                    <th>Complaint #</th>
                                                    <td>{{ $complaint->complaint_no }}</td>
                                                    <th>Complaint Time</th>
                                                    <td>{{ date('d M h:i A', strtotime($complaint->complaint_at)) }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Name</th>
                                                    <td>{{ $complaint->name }}</td>
                                                    <th>Category</th>
                                                    <td>{{ optional($complaint->category)->name }}</td>
                                                </tr>

                                                <tr>
                                                    @if($complaint->cnic)
                                                    <th>CNIC</th>
                                                    <td>{{ addDashesInCNIC($complaint->cnic) }}</td>
                                                    @endif
                                                    @if($complaint->mobile)
                                                    <th>Mobile</th>
                                                    <td>{{ addDashInMobile($complaint->mobile) }}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td colspan="3" class="urduLabel">{{ $complaint->description }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Location</th>
                                                    <td class="urduLabel">{{ $complaint->location }}</td>
                                                    <th>Source</th>
                                                    <td>{{ optional($complaint->source)->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Complaint By</th>
                                                    <td>{{ optional($complaint->complaint_by)->name }}</td>
                                                    <th>Complaint Status</th>
                                                    <td>{!! getComplaintStatusBadge($complaint) !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Attachments</th>
                                                    <td class="attachments" colspan="3">
                                                        @if($complaint->attachment)
                                                            @php
                                                                $ext = strtolower(pathinfo($complaint->attachment, PATHINFO_EXTENSION));
                                                                $videoExt = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                                                            @endphp
                                                            <a href="{{ asset('storage/complaints/' . $complaint->attachment) }}" target="_blank">
                                                                @if($ext === 'pdf')
                                                                    <img src="{{ asset('images/pdf_icon.png') }}" width="120" alt="PDF" />
                                                                @elseif(in_array($ext, $videoExt))
                                                                    <img src="{{ asset('images/vlc_icon.png') }}" width="120" alt="PDF" />
                                                                @else
                                                                    <img src="{{ asset('storage/complaints/' . $complaint->attachment) }}" width="120" />
                                                                @endif
                                                            </a>
                                                        @endif

                                                        @if($complaint->complaint_status == 1 && $complaint->resolved_attachment)
                                                            @php
                                                                $resolvedExt = strtolower(pathinfo($complaint->resolved_attachment, PATHINFO_EXTENSION));
                                                                $videoExt = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                                                            @endphp
                                                            <a href="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" target="_blank" class="ms-5">
                                                                @if($resolvedExt === 'pdf')
                                                                    <img src="{{ asset('images/pdf_icon.png') }}" width="120" alt="PDF" />
                                                                @elseif(in_array($resolvedExt, $videoExt))
                                                                    <img src="{{ asset('images/vlc_icon.png') }}" width="120" alt="PDF" />
                                                                @else
                                                                    <img src="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" width="120" />
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($complaint->department_id > 0)
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{ optional($complaint->department)->name }}</td>
                                                    <th>Assigned By</th>
                                                    <td>{{ optional($complaint->assigned_user)->name }} ({{ date('d M h:i A', strtotime($complaint->assigned_at)) }})</td>
                                                </tr>
                                                @endif

                                                @if($complaint->complaint_status == 1 || $complaint->complaint_status == 3)
                                                <tr>
                                                    <th>Resolved By</th>
                                                    <td>{{ optional($complaint->resolved_user)->name }}</td>
                                                    <th>Resolved At</th>
                                                    <td>{{ date('d M h:i A', strtotime($complaint->resolved_at)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Remarks</th>
                                                    <td colspan="3" class="urduLabel">{{ $complaint->remarks }}</td>
                                                </tr>
                                                @endif

                                                @if($complaint->complaint_status == 3)
                                                <tr>
                                                    <th>Reopened By</th>
                                                    <td>{{ optional($complaint->reopened_user)->name }}</td>
                                                    <th>Reopened Remarks</th>
                                                    <td class="urduLabel">{{ $complaint->reopened_remarks }}</td>
                                                </tr>
                                                @endif

                                            </tbody>
                                            </table>
                                        </div>

                                        @canany(['Complaints Assigned', 'Complaints Rejected'])
                                            @if($complaint->complaint_status == 0 && $complaint->department_id == NULL)
                                            {{ html()->form('POST', route('complaints.assigned', $complaint->uuid))->id('formValidation')->open() }}
                                                <div class="card-body row">
                                                    <div class="form-group col-md-4">
                                                        {{ html()->label()->for('department_id')->text('Department')->class('form-label required-input') }}
                                                        {{ html()->select('department_id', $departments, null)->class('form-select')->placeholder('Select Department')->required() }}
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        @can('Complaints Rejected')
                                                        <button type="button" class="btn btn-danger btn-reject me-2">Rejected</button>
                                                        @endcan
                                                        @can('Complaints Assigned')
                                                        <button type="submit" class="btn btn-primary">Assign</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            {{ html()->form()->close() }}
                                            @endif
                                        @endcan

                                        @canany(['Complaints Reassigned'])
                                            @if($complaint->complaint_status == 0 && $complaint->department_id != NULL)
                                            {{ html()->form('POST', route('complaints.assigned', $complaint->uuid))->id('formValidation')->open() }}
                                                <div class="card-body row">
                                                    <div class="form-group col-md-4">
                                                        {{ html()->label()->for('department_id')->text('Department')->class('form-label required-input') }}
                                                        {{ html()->select('department_id', $departments, null)->class('form-select')->placeholder('Select Department')->required() }}
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary">Re-Assign</button>
                                                    </div>
                                                </div>
                                            {{ html()->form()->close() }}
                                            @endif
                                        @endcan

                                        @canany(['Complaints Resolved'])
                                            @if(in_array($complaint->complaint_status, [0, 3]) && $complaint->department_id != NULL && in_array($complaint->department_id, $user->departments->pluck('id')->toArray()))
                                            {{ html()->form('POST', route('complaints.resolved', $complaint->uuid))->id('formValidation')->attribute('enctype', 'multipart/form-data')->open() }}
                                                <div class="card-body row">
                                                    <div class="form-group col-md-12">
                                                        {{ html()->label()->for('attachment')->text('Attachment')->class('form-label required-input') }} <br/>
                                                        {{ html()->file('attachment')->required() }}
                                                        {!! $errors->first('attachment', '<label class="error">:message</label>') !!}
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        {{ html()->label()->for('remarks')->text('Remarks')->class('form-label required-input') }}
                                                        {{ html()->textarea('remarks', null)->class('form-control')->placeholder('Remarks')->required()->maxlength(500) }}
                                                        {!! $errors->first('remarks', '<label class="error">:message</label>') !!}
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success mr-2">Resolved</button>
                                                    </div>
                                                </div>
                                            {{ html()->form()->close() }}
                                            @endif
                                        @endcan

                                        @canany(['Complaints Reopened'])
                                            @if($complaint->complaint_status == 1)
                                            {{ html()->form('POST', route('complaints.reopened', $complaint->uuid))->id('formValidation')->attribute('enctype', 'multipart/form-data')->open() }}
                                                <div class="card-body row">
                                                    <div class="form-group col-md-6">
                                                        {{ html()->label()->for('reopen_remarks')->text('Reopen Remarks')->class('form-label required-input') }}
                                                        {{ html()->textarea('reopen_remarks', null)->class('form-control')->placeholder('Reopen Remarks')->required()->maxlength(500) }}
                                                        {!! $errors->first('reopen_remarks', '<label class="error">:message</label>') !!}
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success mr-2">Reopened</button>
                                                    </div>
                                                </div>
                                            {{ html()->form()->close() }}
                                            @endif
                                        @endcan

                                    </div>
                                <!-- [ basic-table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->


    @push('scripts')    
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        _$.ready(function () {
            $('#formValidation').validate();

            _$.on('click', '.btn-reject', function (e) {
                e.preventDefault();  

                $.confirm({
                    title: 'Confirm!',
                    content: 'Are you sure! You want to reject this complaint?',
                    type: 'red',
                    typeAnimated: true,
                    closeIcon: true,
                    buttons: {
                        confirm: function () {
                            // loadingOverlay('.btn-reject');
                            $.ajax({
                                type: "get",
                                url: route('complaints.rejected', '{{ $complaint->uuid }}'),
                                dataType: "json",
                                complete:function (res) {
                                    // stopOverlay('.btn-reject');
                                    successMessage('Complaint rejected successfully!');
                                    document.location.href = route('complaints.index');
                                }
                            });                                                                         
                        },
                        cancel: function () { },
                    }
                });

                return false;

            });
        });
    </script>
    @endpush

</x-admin-layout>