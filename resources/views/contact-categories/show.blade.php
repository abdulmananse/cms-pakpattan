<x-admin-layout>
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Category Details" :button="['name' => 'Back to List', 'allow' => true, 'link' => route('contact-categories.index')]" />
                    <!-- [ breadcrumb ] end -->

                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <div class="col-xl-4 col-md-5">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">{{ $contactCategory->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <span class="text-muted d-block small">Status</span>
                                                {!! getStatusBadge($contactCategory->is_active) !!}
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-muted d-block small">Assigned Officers</span>
                                                <span class="badge bg-info text-white fs-6">{{ $contactCategory->officerContacts->count() }} Records</span>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-muted d-block small">Description</span>
                                                <p class="text-dark mb-0">{{ $contactCategory->description ?: 'No description provided.' }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-muted d-block small">Created At</span>
                                                <span>{{ $contactCategory->created_at->format('M d, Y h:i A') }}</span>
                                            </div>
                                            <div class="pt-2 border-top">
                                                <a href="{{ route('contact-categories.edit', $contactCategory->uuid) }}" class="btn btn-primary btn-sm"><i class="feather icon-edit-2 me-1"></i> Edit Category</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-md-7">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Officers in this Category</h5>
                                            <a href="{{ route('officer-contacts.create') }}" class="btn btn-outline-primary btn-sm"><i class="feather icon-plus me-1"></i> Add Officer</a>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Officer</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                        <th>Mobile</th>
                                                        <th>PCM</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($contactCategory->officerContacts as $officer)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $officer->photo_url }}" class="rounded-circle me-2" width="35" height="35" style="object-fit:cover;" />
                                                                <div>
                                                                    <a href="{{ route('officer-contacts.show', $officer->uuid) }}" class="font-weight-bold text-dark">{{ $officer->officer_name }}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $officer->designation ?: '-' }}</td>
                                                        <td>{{ $officer->department ? $officer->department->name : '-' }}</td>
                                                        <td><a href="tel:{{ $officer->primary_mobile }}">{{ $officer->primary_mobile }}</a></td>
                                                        <td>
                                                            @if($officer->is_pcm)
                                                                <span class="badge bg-success">PCM</span>
                                                            @else
                                                                <span class="badge bg-secondary">Non-PCM</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('officer-contacts.show', $officer->uuid) }}" class="btn btn-icon btn-sm btn-info" title="View"><i class="feather icon-eye"></i></a>
                                                            <a href="{{ route('officer-contacts.edit', $officer->uuid) }}" class="btn btn-icon btn-sm btn-secondary" title="Edit"><i class="feather icon-edit-2"></i></a>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">No officers assigned to this category yet.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
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
