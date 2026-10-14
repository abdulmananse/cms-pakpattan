<x-admin-layout>
    @include('revenue-matters.partials.theme-styles')

    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Lodge Land Revenue Complaint" :breadcrumbs="[
                ['name' => 'Land Revenue Matters', 'allow' => true, 'link' => route('revenue-matters.dashboard')],
                ['name' => 'Complaints', 'allow' => true, 'link' => route('revenue-matters.complaints.index')],
                ['name' => 'Lodge New Complaint', 'allow' => true, 'link' => '#'],
            ]" />
            <!-- [ breadcrumb ] end -->

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="card shadow-sm border-0 revenue-form-card">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="text-white mb-0 fw-bold" style="font-size: 19px;">
                                <i class="feather icon-plus-circle me-1"></i> Register New Land Revenue Matter
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger" style="font-size: 14px;">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('revenue-matters.complaints.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <h6 class="form-section-heading">
                                    <i class="feather icon-user me-1"></i> Citizen Information
                                </h6>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="citizen_name" class="form-label fw-bold">Citizen Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('citizen_name') is-invalid @enderror" 
                                               id="citizen_name" name="citizen_name" value="{{ old('citizen_name') }}" required>
                                        @error('citizen_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="father_husband_name" class="form-label fw-bold">Father / Husband Name</label>
                                        <input type="text" class="form-control @error('father_husband_name') is-invalid @enderror" 
                                               id="father_husband_name" name="father_husband_name" value="{{ old('father_husband_name') }}">
                                        @error('father_husband_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="cnic" class="form-label fw-bold">CNIC</label>
                                        <input type="text" class="form-control @error('cnic') is-invalid @enderror" 
                                               id="cnic" name="cnic" placeholder="e.g. 35202-1234567-1" value="{{ old('cnic') }}">
                                        @error('cnic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="contact_number" class="form-label fw-bold">Contact Number</label>
                                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                               id="contact_number" name="contact_number" placeholder="e.g. 03001234567" value="{{ old('contact_number') }}">
                                        @error('contact_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="tehsil" class="form-label fw-bold">Tehsil</label>
                                        <select class="form-select @error('tehsil') is-invalid @enderror" id="tehsil" name="tehsil">
                                            <option value="">-- Select Tehsil --</option>
                                            <option value="Pakpattan" {{ old('tehsil') === 'Pakpattan' ? 'selected' : '' }}>Pakpattan</option>
                                            <option value="Arifwala" {{ old('tehsil') === 'Arifwala' ? 'selected' : '' }}>Arifwala</option>
                                        </select>
                                        @error('tehsil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="address" class="form-label fw-bold">Address / Mauza / Location</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <h6 class="form-section-heading">
                                    <i class="feather icon-file-text me-1"></i> Complaint Details
                                </h6>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label for="subject" class="form-label fw-bold">Subject / Matter Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                               id="subject" name="subject" value="{{ old('subject') }}" 
                                               placeholder="e.g. Demarcation of land in Mauza..., Correction of Revenue Record" required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="description" class="form-label fw-bold">Detailed Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="attachments" class="form-label fw-bold">Initial Supporting Documents / Attachments</label>
                                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                                               id="attachments" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <small class="text-muted d-block mt-1" style="font-size: 14px;">Allowed types: PDF, JPG, PNG, DOC, DOCX. Max: 10MB per file. You can select multiple files.</small>
                                        @error('attachments.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between pt-2">
                                    <a href="{{ route('revenue-matters.complaints.index') }}" class="btn btn-secondary revenue-form-btn">
                                        <i class="feather icon-arrow-left me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 revenue-form-btn">
                                        <i class="feather icon-check-circle me-1"></i> Lodge Land Revenue Matter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
