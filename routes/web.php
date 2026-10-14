<?php

use App\Http\Controllers\Acl\PermissionController;
use App\Http\Controllers\Acl\PermissionGroupController;
use App\Http\Controllers\Acl\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FrontComplaintController;
use App\Http\Controllers\OfficerContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Revenue\RevenueComplaintActionController;
use App\Http\Controllers\Revenue\RevenueComplaintController;
use App\Http\Controllers\Revenue\RevenueDashboardController;
use App\Models\PermissionGroup;
use App\Models\RevenueComplaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'landing'])->name('landing');

# Complaint
Route::get('complaint', [FrontComplaintController::class, 'create'])->name('complaint');
Route::post('complaint', [FrontComplaintController::class, 'store'])->name('complaint.store');
Route::post('complaint-status', [FrontComplaintController::class, 'checkStatus'])->name('complaint-status.check');
Route::post('complaint-feedback/{complaint}', [FrontComplaintController::class, 'submitFeedback'])->name('complaint-feedback.submit');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('complaint/{complaint}', [FrontComplaintController::class, 'show'])->name('complaint.show');
    
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('update-profile', [UserController::class, 'editProfile'])->name('edit-profile');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('update-password');

    # Complaints 
    Route::resource('complaints', ComplaintController::class)->only('index')->middleware('permission:Complaints Index');
    Route::resource('complaints', ComplaintController::class)->only(['create', 'store'])->middleware('permission:Register Complaint');
    Route::resource('complaints', ComplaintController::class)->only('show')->middleware('permission:Complaints Show');
    Route::post('complaints/datatable', [ComplaintController::class, 'index'])->name('complaints.datatable');
    Route::post('complaints/assign/{complaint}', [ComplaintController::class, 'assigned'])->name('complaints.assigned')->middleware('permission:Complaints Assigned');
    Route::post('complaints/transfer/{complaint}', [ComplaintController::class, 'transfer'])->name('complaints.transfer')->middleware('permission:Complaints Transfer');
    Route::get('complaints/reject/{complaint}', [ComplaintController::class, 'rejected'])->name('complaints.rejected')->middleware('permission:Complaints Rejected');
    Route::post('complaints/resolved/{complaint}', [ComplaintController::class, 'resolved'])->name('complaints.resolved')->middleware('permission:Complaints Resolved');
    Route::post('complaints/reopened/{complaint}', [ComplaintController::class, 'reopened'])->name('complaints.reopened')->middleware('permission:Complaints Reopened');
    Route::post('complaints/feedback/{complaint}', [ComplaintController::class, 'feedback'])->name('complaints.feedback')->middleware('permission:Complaints Feedback');

    # Revenue Department Matters
    Route::model('revenue_complaint', RevenueComplaint::class);
    Route::prefix('revenue-matters')->name('revenue-matters.')->group(function () {
        Route::get('dashboard', [RevenueDashboardController::class, 'index'])->name('dashboard')->middleware('permission:Land Revenue Matters Access|Land Revenue Matters Dashboard|Revenue Matters Access');
        Route::get('complaints', [RevenueComplaintController::class, 'index'])->name('complaints.index')->middleware('permission:Land Revenue Matters Access|Revenue Matters Access');
        Route::get('complaints/create', [RevenueComplaintController::class, 'create'])->name('complaints.create')->middleware('permission:Land Revenue Matters Create|Revenue Matters Create');
        Route::post('complaints', [RevenueComplaintController::class, 'store'])->name('complaints.store')->middleware('permission:Land Revenue Matters Create|Revenue Matters Create');
        Route::get('complaints/search', [RevenueComplaintController::class, 'search'])->name('complaints.search')->middleware('permission:Land Revenue Matters Access|Land Revenue Matters Search|Revenue Matters Access');
        Route::get('complaints/{revenue_complaint}', [RevenueComplaintController::class, 'show'])->name('complaints.show');

        // Revenue Complaint Actions
        Route::post('complaints/{revenue_complaint}/forward', [RevenueComplaintActionController::class, 'forward'])->name('complaints.forward')->middleware('permission:Land Revenue Matters Forward|Revenue Matters Forward');
        Route::post('complaints/{revenue_complaint}/send-back', [RevenueComplaintActionController::class, 'sendBack'])->name('complaints.sendBack')->middleware('permission:Land Revenue Matters Send Back|Revenue Matters Send Back');
        Route::post('complaints/{revenue_complaint}/comment', [RevenueComplaintActionController::class, 'addComment'])->name('complaints.comment')->middleware('permission:Land Revenue Matters Comment|Revenue Matters Comment');
        Route::post('complaints/{revenue_complaint}/close', [RevenueComplaintActionController::class, 'close'])->name('complaints.close');
        Route::post('complaints/{revenue_complaint}/reopen', [RevenueComplaintActionController::class, 'reopen'])->name('complaints.reopen');
    });

    # Reports
    Route::get('reports/charts', [ReportController::class, 'charts'])->name('reports.charts')->middleware('permission:Charts');
    Route::get('reports/summary', [ReportController::class, 'summary'])->name('reports.summary')->middleware('permission:Summary');
    Route::get('reports/pending-complaints', [ReportController::class, 'pendingComplaints'])->name('reports.pendingComplaints')->middleware('permission:Pending Complaints');
    Route::get('reports/resolved-complaints', [ReportController::class, 'resolvedComplaints'])->name('reports.resolvedComplaints')->middleware('permission:Resolved Complaints');
    Route::get('reports/complaints-download', [ReportController::class, 'downloadView'])->name('reports.download');
    Route::post('reports/complaints-download', [ReportController::class, 'downloadExport'])->name('reports.download.export');

    # Users 
    Route::resource('users', UserController::class)->only('index')->middleware('permission:Users Index');
    Route::resource('users', UserController::class)->only(['create', 'store'])->middleware('permission:Users Create');
    Route::resource('users', UserController::class)->only(['edit', 'update'])->middleware('permission:Users Update');
    Route::resource('users', UserController::class)->only('destroy')->middleware('permission:Users Delete');
    Route::post('users/datatable', [UserController::class, 'index'])->name('users.datatable')->middleware('permission:Users Index');
    Route::get('users/update-status/{user}', [UserController::class, 'updateStatus'])->name('users.updateStatus')->middleware('permission:Users Update Status');

    # Department 
    Route::resource('departments', DepartmentController::class);
    Route::post('departments/datatable', [DepartmentController::class, 'index'])->name('departments.datatable');
    Route::get('departments/update-status/{department}', [DepartmentController::class, 'updateStatus'])->name('departments.updateStatus');

    # Category 
    Route::resource('categories', CategoryController::class);
    Route::post('categories/datatable', [CategoryController::class, 'index'])->name('categories.datatable');
    Route::get('categories/update-status/{category}', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');

    # Source 
    Route::resource('sources', SourceController::class);
    Route::post('sources/datatable', [SourceController::class, 'index'])->name('sources.datatable');
    Route::get('sources/update-status/{source}', [SourceController::class, 'updateStatus'])->name('sources.updateStatus');

    # Contact Categories
    Route::resource('contact-categories', ContactCategoryController::class);
    Route::post('contact-categories/datatable', [ContactCategoryController::class, 'index'])->name('contact-categories.datatable');
    Route::get('contact-categories/update-status/{contact_category}', [ContactCategoryController::class, 'updateStatus'])->name('contact-categories.updateStatus');

    # Officer Contact Records
    Route::resource('officer-contacts', OfficerContactController::class);
    Route::post('officer-contacts/datatable', [OfficerContactController::class, 'index'])->name('officer-contacts.datatable');
    Route::get('officer-contacts/toggle-pcm/{officer_contact}', [OfficerContactController::class, 'togglePcm'])->name('officer-contacts.togglePcm');
    Route::get('officer-contacts/toggle-fav/{officer_contact}', [OfficerContactController::class, 'toggleFavorite'])->name('officer-contacts.toggleFavorite');
    Route::get('officer-contacts/update-status/{officer_contact}', [OfficerContactController::class, 'updateStatus'])->name('officer-contacts.updateStatus');

    # ACL 
    Route::resource('roles', RoleController::class)->only('index')->middleware('permission:Roles Index');
    Route::resource('roles', RoleController::class)->only(['create', 'store'])->middleware('permission:Roles Create');
    Route::resource('roles', RoleController::class)->only(['edit', 'update'])->middleware('permission:Roles Update');
    Route::resource('roles', RoleController::class)->only('destroy')->middleware('permission:Roles Delete');
    Route::post('roles/datatable', [RoleController::class, 'index'])->name('roles.datatable')->middleware('permission:Roles Index');
    
    Route::get('roles/update-status/{role}', [RoleController::class, 'updateStatus'])->name('roles.updateStatus');
    Route::get('roles/permissions/{role}', [RoleController::class, 'getRolePermissions'])->name('roles.getPermissions');
    Route::put('roles/permissions/{role}', [RoleController::class, 'updateRolePermission'])->name('roles.permissions');
    
    Route::model('permission_group', PermissionGroup::class);

    Route::resource('permission-groups', PermissionGroupController::class);
    Route::post('permission-groups/datatable', [PermissionGroupController::class, 'index'])->name('permission-groups.datatable');

    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/datatable', [PermissionController::class, 'index'])->name('permissions.datatable');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:Settings Index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:Settings Update');
});

require __DIR__.'/auth.php';
