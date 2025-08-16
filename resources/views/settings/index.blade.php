<x-admin-layout>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">

                    <!-- [ breadcrumb ] start -->
                    <x-breadcrumb title="Settings" :breadcrumbs="[['name' => 'General Settings', 'allow' => true, 'link' => '#']]" />
                    <!-- [ breadcrumb ] end -->

                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ basic-table ] start -->
                                <div class="col-xl-12">
                                    <div class="card card-custom gutter-b example example-compact">
                                        <!--begin::Form-->
                                        {{ html()->form('POST', route('settings.update'))->id('formValidation')->open() }}
                                        <div class="card-body row">
                                           <div class="form-group col-md-6">
                                                {{ html()->label()->for('site_name')->text('Site Name')->class('form-label required-input') }}
                                                {{ html()->text('site_name')->value(@$settings['site_name'])->class('form-control')->classIf($errors->has('site_name'), 'error')->placeholder('Site Name')->maxlength(50)->required() }}
                                            </div>
                                        </div>

                                        @can('Settings Update')
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                                            <button type="button" onclick="window.location='{{ URL::previous() }}'"
                                                class="btn btn-secondary">Cancel</button>
                                        </div>
                                        @endcan

                                        {{ html()->form()->close() }}
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

</x-admin-layout>
