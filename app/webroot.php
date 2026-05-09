<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    RoleController, PrivilegeController, UserController,
    ClassesController, GroupController, CategoryController,
    ResourceTypeController, ResourceController, DashboardController as AdminDashboard
};
//use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;


use App\Http\Controllers\Student\{
    DashboardController as StudentDashboard, ResourceController as StudentResource
};

// Default welcome

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Admin Routes (with middleware)
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('privileges', PrivilegeController::class);
    Route::resource('users', UserController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('resource-types', ResourceTypeController::class);
    Route::resource('resources', ResourceController::class);
    // Resource Toggles
Route::patch('resources/{resource}/toggle-active', [ResourceController::class, 'toggleActive'])->name('resources.toggle-active');
Route::patch('resources/{resource}/toggle-download', [ResourceController::class, 'toggleDownload'])->name('resources.toggle-download');
Route::patch('resources/{resource}/toggle-view', [ResourceController::class, 'toggleView'])->name('resources.toggle-view');
Route::patch('resources/{resource}/toggle-online-read', [ResourceController::class, 'toggleOnlineRead'])->name('resources.toggle-online-read');

// Other Toggles
Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
Route::patch('classes/{classes}/toggle-active', [ClassesController::class, 'toggleActive'])->name('classes.toggle-active');
Route::patch('groups/{group}/toggle-active', [GroupController::class, 'toggleActive'])->name('groups.toggle-active');
Route::patch('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::resource('resources', \App\Http\Controllers\Admin\ResourceController::class);
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

});

// Student Routes (with middleware)
Route::prefix('student')->middleware(['auth', 'is_student'])->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
    Route::get('/resources', [StudentResource::class, 'index'])->name('student.resources');
    Route::get('/resources/{id}', [StudentResource::class, 'show'])->name('student.resources.show');
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
Route::get('/student/resources/{id}', [StudentDashboardController::class, 'showResource'])->name('student.resource.show');

Route::prefix('student')->middleware(['auth','role:student'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
});

});

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);


// require __DIR__.'/auth.php';
