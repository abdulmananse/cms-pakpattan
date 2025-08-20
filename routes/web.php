<?php

use App\Http\Controllers\Acl\PermissionController;
use App\Http\Controllers\Acl\PermissionGroupController;
use App\Http\Controllers\Acl\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FrontComplaintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\Complaint;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

# Complaint
Route::get('complaint', [FrontComplaintController::class, 'create'])->name('complaint');
Route::post('complaint', [FrontComplaintController::class, 'store'])->name('complaint.store');
Route::get('complaint-status', [FrontComplaintController::class, 'status'])->name('complaint-status');
Route::post('complaint-status', [FrontComplaintController::class, 'checkStatus'])->name('complaint-status.check');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::resource('complaints', ComplaintController::class)->only('show')->middleware('permission:Complaints Show');
    Route::post('complaints/datatable', [ComplaintController::class, 'index'])->name('complaints.datatable');
    Route::post('complaints/assign/{complaint}', [ComplaintController::class, 'assigned'])->name('complaints.assigned')->middleware('permission:Complaints Assigned');
    Route::get('complaints/reject/{complaint}', [ComplaintController::class, 'rejected'])->name('complaints.rejected')->middleware('permission:Complaints Rejected');

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
