<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Officer Contact Details" :button="['name' => 'Back to Listing', 'allow' => true, 'link' => route('officer-contacts.index')]" />
                    <!-- [ breadcrumb ] end -->

                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <!-- Officer Summary Card -->
                                <div class="col-xl-4 col-md-5">
                                    <div class="card text-center p-3">
                                        <div class="card-body">
                                            <div class="mb-3 position-relative d-inline-block">
                                                <img src="{{ $officerContact->photo_url }}"
                                                     alt="{{ $officerContact->officer_name }}"
                                                     class="img-thumbnail rounded-circle shadow-sm"
                                                     style="width: 140px; height: 140px; object-fit: cover;" />
                                                @if($officerContact->is_favorite)
                                                    <span class="position-absolute bottom-0 end-0 bg-warning text-white rounded-circle p-2 shadow-sm" title="Marked as Favorite">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                            </div>

                                            <h5 class="mb-1 font-weight-bold">{{ $officerContact->officer_name }}</h5>
                                            <p class="text-muted mb-2">{{ $officerContact->designation ?: 'Designation not specified' }}</p>

                                            <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                                @if($officerContact->is_pcm)
                                                    <span class="badge bg-success">PCM</span>
                                                @else
                                                    <span class="badge bg-secondary">Non-PCM</span>
                                                @endif

                                                @if(strtolower($officerContact->lifecycle_status) === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </div>

                                            <div class="d-grid gap-2 mt-4">
                                                <a href="{{ route('officer-contacts.edit', $officerContact->uuid) }}" class="btn btn-primary btn-sm">
                                                    <i class="feather icon-edit-2 me-1"></i> Edit Officer Record
                                                </a>
                                                <a href="tel:{{ $officerContact->primary_mobile }}" class="btn btn-outline-success btn-sm">
                                                    <i class="feather icon-phone-call me-1"></i> Call Mobile
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detailed Information -->
                                <div class="col-xl-8 col-md-7">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0 font-weight-bold">Complete Officer Profile</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Full Name</span>
                                                    <strong class="fs-6 text-dark">{{ $officerContact->officer_name }}</strong>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Designation</span>
                                                    <span class="fs-6 text-dark">{{ $officerContact->designation ?: '-' }}</span>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Department</span>
                                                    <span class="fs-6 text-dark font-weight-bold">{{ $officerContact->department ? $officerContact->department->name : '-' }}</span>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Category Group</span>
                                                    <span class="badge bg-light text-dark border fs-6">{{ $officerContact->contactCategory ? $officerContact->contactCategory->name : '-' }}</span>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Office / Establishment</span>
                                                    <span class="fs-6 text-dark">{{ $officerContact->office_establishment ?: '-' }}</span>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Primary Mobile #</span>
                                                    <a href="tel:{{ $officerContact->primary_mobile }}" class="fs-6 text-primary font-weight-bold">
                                                        <i class="feather icon-phone me-1"></i>{{ $officerContact->primary_mobile }}
                                                    </a>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Alternate Phone # (Office)</span>
                                                    @if($officerContact->alternate_phone)
                                                        <a href="tel:{{ $officerContact->alternate_phone }}" class="fs-6 text-dark">
                                                            <i class="feather icon-phone-forwarded me-1"></i>{{ $officerContact->alternate_phone }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Email Address</span>
                                                    @if($officerContact->email)
                                                        <a href="mailto:{{ $officerContact->email }}" class="fs-6 text-primary">
                                                            <i class="feather icon-mail me-1"></i>{{ $officerContact->email }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Price Control Magistrate (PCM)</span>
                                                    @if($officerContact->is_pcm)
                                                        <span class="text-success font-weight-bold"><i class="feather icon-check-circle me-1"></i>Yes, Authorized PCM</span>
                                                    @else
                                                        <span class="text-muted"><i class="feather icon-x-circle me-1"></i>No</span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Favorite Flag</span>
                                                    @if($officerContact->is_favorite)
                                                        <span class="text-warning font-weight-bold"><i class="fas fa-star me-1"></i>Marked as Favorite</span>
                                                    @else
                                                        <span class="text-muted">Standard Contact</span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Created At</span>
                                                    <span>{{ $officerContact->created_at->format('M d, Y h:i A') }}</span>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <span class="text-muted small d-block">Last Updated</span>
                                                    <span>{{ $officerContact->updated_at->format('M d, Y h:i A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
