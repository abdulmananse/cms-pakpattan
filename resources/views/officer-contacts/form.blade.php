<div class="col-12 mb-4 pb-3 border-bottom">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="mb-2 mb-md-0 text-uppercase font-weight-bold text-dark">
            {{ @$officer ? 'Edit Officer Contact Details' : 'Officer Information' }}
        </h5>
        <div class="d-flex align-items-center bg-light px-3 py-2 rounded border">
            <div class="form-check form-switch me-4 d-flex align-items-center">
                {{ html()->hidden('is_pcm', '0') }}
                {{ html()->checkbox('is_pcm', old('is_pcm', @$officer ? $officer->is_pcm : false), '1')->class('form-check-input me-2')->id('is_pcm')->style('cursor:pointer; width: 1.8em; height: 1em;') }}
                {{ html()->label('Price Control Magistrate (PCM)', 'is_pcm')->class('form-check-label font-weight-bold text-dark mb-0')->style('cursor:pointer;') }}
            </div>
            <div class="form-check form-switch ms-3 d-flex align-items-center">
                {{ html()->hidden('is_favorite', '0') }}
                {{ html()->checkbox('is_favorite', old('is_favorite', @$officer ? $officer->is_favorite : false), '1')->class('form-check-input me-2')->id('is_favorite')->style('cursor:pointer; width: 1.8em; height: 1em;') }}
                {{ html()->label('<i class="fas fa-star text-warning me-1"></i> Favorite', 'is_favorite')->class('form-check-label font-weight-bold text-dark mb-0')->style('cursor:pointer;') }}
            </div>
        </div>
    </div>
</div>

<!-- Main Form Grid -->
<div class="col-xl-3 col-lg-4 col-md-12 mb-4 text-center">
    <div class="p-3 border rounded bg-light">
        <label class="form-label font-weight-bold d-block text-muted mb-2">Officer Photo</label>
        <div class="position-relative d-inline-block">
            <img id="photo-preview"
                 src="{{ @$officer && $officer->photo ? $officer->photo_url : asset('images/avatar-1.png') }}"
                 alt="Officer Photo Preview"
                 class="img-thumbnail rounded shadow-sm"
                 style="width: 170px; height: 170px; object-fit: cover; background: #fff;" />
        </div>
        <div class="mt-3">
            <label for="photo-input" class="btn btn-primary btn-sm mb-1" style="cursor: pointer;">
                <i class="feather icon-upload me-1"></i> Upload Photo
            </label>
            <input type="file" id="photo-input" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="d-none" onchange="previewOfficerImage(this);" />
            <div class="text-muted small mt-1">JPG, PNG, WEBP &bull; Max 2MB</div>
            {!! $errors->first('photo', '<label class="error d-block">:message</label>') !!}
        </div>
    </div>
</div>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <!-- Officer Name -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('officer_name')->text('Officer Name')->class('form-label required-input') }}
            {{ html()->text('officer_name')->value(old('officer_name', @$officer->officer_name))->class('form-control')->classIf($errors->has('officer_name'), 'error')->placeholder('e.g. Muhammad Ali Khan')->maxlength(255)->required() }}
            {!! $errors->first('officer_name', '<label class="error">:message</label>') !!}
        </div>

        <!-- Designation -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('designation')->text('Designation')->class('form-label') }}
            {{ html()->text('designation')->value(old('designation', @$officer->designation))->class('form-control')->classIf($errors->has('designation'), 'error')->placeholder('e.g. Assistant Commissioner')->maxlength(255) }}
            {!! $errors->first('designation', '<label class="error">:message</label>') !!}
        </div>

        <!-- Department -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('department_id')->text('Department')->class('form-label required-input') }}
            {{ html()->select('department_id', $departments, old('department_id', @$officer->department_id))->class('form-select select2-dept')->classIf($errors->has('department_id'), 'error')->placeholder('Select Department')->required() }}
            {!! $errors->first('department_id', '<label class="error">:message</label>') !!}
        </div>

        <!-- Office / Establishment -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('office_establishment')->text('Office / Establishment')->class('form-label') }}
            {{ html()->text('office_establishment')->value(old('office_establishment', @$officer->office_establishment))->class('form-control')->classIf($errors->has('office_establishment'), 'error')->placeholder('e.g. DC Office Pakpattan')->maxlength(255) }}
            {!! $errors->first('office_establishment', '<label class="error">:message</label>') !!}
        </div>

        <!-- Primary Mobile # -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('primary_mobile')->text('Primary Mobile #')->class('form-label required-input') }}
            {{ html()->text('primary_mobile')->value(old('primary_mobile', @$officer->primary_mobile))->class('form-control mobile-mask')->classIf($errors->has('primary_mobile'), 'error')->placeholder('03xx-xxxxxxx')->maxlength(30)->required() }}
            {!! $errors->first('primary_mobile', '<label class="error">:message</label>') !!}
        </div>

        <!-- Alternate Phone # (Office) -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('alternate_phone')->text('Alternate Phone # (Office)')->class('form-label') }}
            {{ html()->text('alternate_phone')->value(old('alternate_phone', @$officer->alternate_phone))->class('form-control mobile-mask')->classIf($errors->has('alternate_phone'), 'error')->placeholder('e.g. 03xx-xxxxxxx/0457-xxxxxx')->maxlength(30) }}
            {!! $errors->first('alternate_phone', '<label class="error">:message</label>') !!}
        </div>

        <!-- Email Address -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('email')->text('Email Address')->class('form-label') }}
            {{ html()->email('email')->value(old('email', @$officer->email))->class('form-control')->classIf($errors->has('email'), 'error')->placeholder('officer@punjab.gov.pk')->maxlength(255) }}
            {!! $errors->first('email', '<label class="error">:message</label>') !!}
        </div>

        <!-- Category Group -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('contact_category_id')->text('Category Group')->class('form-label required-input') }}
            {{ html()->select('contact_category_id', $categories, old('contact_category_id', @$officer->contact_category_id))->class('form-select select2-cat')->classIf($errors->has('contact_category_id'), 'error')->placeholder('Select Category Group')->required() }}
            {!! $errors->first('contact_category_id', '<label class="error">:message</label>') !!}
        </div>

        <!-- Lifecycle Status -->
        <div class="form-group col-md-6 mb-3">
            {{ html()->label()->for('lifecycle_status')->text('Lifecycle Status')->class('form-label required-input') }}
            {{ html()->select('lifecycle_status', ['Active' => 'Active', 'Inactive' => 'Inactive'], old('lifecycle_status', @$officer ? $officer->lifecycle_status : 'Active'))->class('form-select')->classIf($errors->has('lifecycle_status'), 'error')->required() }}
            {!! $errors->first('lifecycle_status', '<label class="error">:message</label>') !!}
        </div>
    </div>
</div>
