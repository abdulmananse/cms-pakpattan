<x-admin-layout>
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
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Complaint Details</h5>
                                        </div>
                                        <div class="card-body border-top pro-det-edit collapse show" id="pro-det-edit-1">
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Complaint #</label>
                                                <div class="col-sm-9">
                                                    {{ $complaint->complaint_no }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Name</label>
                                                <div class="col-sm-9">
                                                    {{ $complaint->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">CNIC</label>
                                                <div class="col-sm-9">
                                                    {{ addDashesInCNIC($complaint->cnic) }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Mobile</label>
                                                <div class="col-sm-9">
                                                    {{ addDashInMobile($complaint->mobile) }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Category</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->category)->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Description</label>
                                                <div class="col-sm-9">
                                                    {{ $complaint->description }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Location</label>
                                                <div class="col-sm-9">
                                                    {{ $complaint->location }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Source</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->source)->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Complaint By</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->complaint_by)->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Attachment</label>
                                                <div class="col-sm-9">
                                                    @if($complaint->attachment)
                                                        @php
                                                            $ext = strtolower(pathinfo($complaint->attachment, PATHINFO_EXTENSION));
                                                        @endphp
                                                        <a href="{{ asset('storage/complaints/' . $complaint->attachment) }}" target="_blank">
                                                            @if($ext === 'pdf')
                                                                <img src="{{ asset('images/pdf-icon.png') }}" width="40" alt="PDF" />
                                                            @else
                                                                <img src="{{ asset('storage/complaints/' . $complaint->attachment) }}" width="80" />
                                                            @endif
                                                        </a>
                                                    @endif

                                                    @if($complaint->complaint_status == 1 && $complaint->resolved_attachment)
                                                        @php
                                                            $resolvedExt = strtolower(pathinfo($complaint->resolved_attachment, PATHINFO_EXTENSION));
                                                        @endphp
                                                        <a href="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" target="_blank" class="ms-3">
                                                            @if($resolvedExt === 'pdf')
                                                                <img src="{{ asset('images/pdf-icon.png') }}" width="40" alt="PDF" />
                                                            @else
                                                                <img src="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" width="80" />
                                                            @endif
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($complaint->department_id > 0)
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Department</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->department)->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Assigned By</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->assigned_user)->name }}
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Status</label>
                                                <div class="col-sm-9">
                                                    {!! getComplaintStatusBadge($complaint) !!}
                                                </div>
                                            </div>

                                            @if($complaint->complaint_status == 1)
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Resolved By</label>
                                                <div class="col-sm-9">
                                                    {{ optional($complaint->resolved_user)->name }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label font-weight-bolder">Remarks</label>
                                                <div class="col-sm-9">
                                                    {{ $complaint->remarks }}
                                                </div>
                                            </div>
                                            @endif
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

                                        @canany(['Complaints Resolved'])
                                            @if($complaint->complaint_status == 0 && $complaint->department_id != NULL && $complaint->department_id == $user->department_id)
                                            {{ html()->form('POST', route('complaints.resolved', $complaint->uuid))->id('formValidation')->attribute('enctype', 'multipart/form-data')->open() }}
                                                <div class="card-body row">
                                                    <div class="form-group col-md-12">
                                                        {{ html()->label()->for('attachment')->text('Attachment')->class('form-label required-input') }} <br/>
                                                        {{ html()->file('attachment')->required() }}
                                                        {!! $errors->first('attachment', '<label class="error">:message</label>') !!}
                                                    </div>
                                                    <div class="form-group col-md-4">
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
                                    </div>
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