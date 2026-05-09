<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



// require __DIR__.'/auth.php';


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    RoleController, PrivilegeController, UserController,AdminDashboardController,SchoolAdminMapperController,
    ClassesController, GroupController, CategoryController,ResourceAccessController,DataHandlerController,
    ResourceTypeController, ArmController,DepartmentController,ResourceController,ItemCategoryController
};
use App\Http\Controllers\Admin\DataMapperController;
//use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Student\{
    DashboardController as StudentDashboard, ResourceController as StudentResource,StudentDashboardController
};
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Auth;


//Auth::routes();
Auth::routes(['reset' => false]);
// Default welcome

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/school-domains', [SchoolController::class, 'domains']);
Route::get('/schools/{subdomain}/students', [SchoolController::class, 'students']);
});

// routes/web.php
Route::get('/user/details/{id}', [UserController::class, 'getDetails'])->name('user.details');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
// Admin Routes (with middleware)




Route::prefix('admin')->middleware(['auth', 'IsAdmin'])->namespace('Admin')->group(function () {
  Route::get('/school_admin_mappers/download', [SchoolAdminMapperController::class, 'download'])
     ->name('school_admin_mappers.download');
});
Route::prefix('admin')->middleware(['auth', 'IsAdmin'])->group(function () {
  //  Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.admin.dashboard');
Route::resource('school_admin_mappers', SchoolAdminMapperController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('privileges', PrivilegeController::class);
    Route::resource('users', UserController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('categories', CategoryController::class);
     Route::resource('arms', ArmController::class);
       Route::resource('item_categories', ItemCategoryController::class);
      Route::resource('departments', DepartmentController::class);
    Route::resource('resourceTypes', ResourceTypeController::class);
    Route::resource('resources', ResourceController::class);
 Route::resource('data-mapper', DataMapperController::class);
    Route::get('/data-handler', [DataHandlerController::class, 'index'])->name('data-handler');
Route::post('/data-handler/load', [DataHandlerController::class, 'loadFromUrl'])->name('load-from-url');
Route::get('/data-handler/tables', [DataHandlerController::class, 'getTables'])->name('get-tables');
Route::get('/data-handler/columns', [DataHandlerController::class, 'getColumns'])->name('get-columnsd');
Route::post('/data-handler/transfer', [DataHandlerController::class, 'transferData'])->name('transfer-data');
    // Resource Toggles
Route::patch('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
Route::patch('arms/{arm}/toggle-active', [ArmController::class, 'toggleActive'])->name('arms.toggle-active');
Route::patch('item_categories/{item_category}/toggle-active', [ItemCategoryController::class, 'toggleActive'])->name('item_category.toggle-active');
Route::patch('departments/{department}/toggle-active', [DepartmentController::class, 'toggleActive'])->name('departments.toggle-active');
//        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
// Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
// Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
//  Route::put('/category/{category}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
// Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

Route::put('categories/{id}', [CategoryController::class, 'edit'])->name('admin.categories.edit');
Route::put('arms/{id}', [ArmController::class, 'edit'])->name('admin.arms.edit');
Route::put('item_categories/{id}', [ArmController::class, 'edit'])->name('admin.item_category.edit');
Route::put('departments/{id}', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
// routes/web.php
// Route::get('/school_admin_mappers/user-details', [App\Http\Controllers\Admin\SchoolAdminMapperController::class, 'getUserDetails'])
//     ->name('admin.user.details');
// Route::get('schoolAdminMappers/user-details/{email}', [SchoolAdminMapperController::class, 'getUserDetails'])
//     ->name('school_admin_mappers.user.details');
    Route::get('schoolAdminMappers/user-details', [SchoolAdminMapperController::class, 'getUserDetails'])
    ->name('school_admin_mappers.user.details');


    Route::patch('role/{role}/toggle-active', [RoleController::class, 'toggleActive'])->name('roles.toggle-active');
    Route::patch('permission/{permission}/toggle-active', [PermissionController::class, 'toggleActive'])->name('permissions.toggle-active');
    Route::patch('resourceTypes/{resource_type}/toggle-active', [ResourceTypeController::class, 'toggleActive'])->name('resource_types.toggle-active');
Route::patch('resources/{resource}/toggle-active', [ResourceController::class, 'toggleActive'])->name('resources.toggle-active');
Route::patch('resources/{resource}/toggle-feature', [ResourceController::class, 'toggleFeature'])->name('resources.toggle-feature');
Route::patch('classes/{class}/toggle-active', [ClassesController::class, 'toggleActive'])->name('classes.toggle-active');
Route::patch('school_admin_mappers/{schoolAdminMapper}/toggle-active', [SchoolAdminMapperController::class, 'toggleActive'])->name('schoolAdminMapper.toggle-active');
Route::patch('resources/{resource}/toggle-download', [ResourceController::class, 'toggleDownload'])->name('resources.toggle-download');
Route::patch('resources/{resource}/toggle-view', [ResourceController::class, 'toggleView'])->name('resources.toggle-view');
Route::patch('resources/{resource}/toggle-online-read', [ResourceController::class, 'toggleOnlineRead'])->name('resources.toggle-online-read');
//Route::patch('resources/{resource}/toggle-online-read', [ClassesController::class, 'toggleOnlineRead'])->name('resources.toggle-online-read');
  Route::post('/resources', [ResourceController::class, 'store'])->name('admin.resources.store');
Route::get('/resources/create', [ResourceController::class, 'create'])->name('admin.resources.create');
 Route::post('/resource/{resource}', [ResourceController::class, 'update'])->name('admin.resources.update');
Route::delete('resources', [ResourceController::class, 'destroy'])->name('admin.resources.destroy');
Route::put('resources/{id}', [ResourceController::class, 'edit'])->name('admin.resources.edit');

  // Route::get('/admin-mappings/{id}', [AdminMappingController::class, 'show'])->name('admin-mappings.show');

 Route::post('class/{class}/update', [ClassesController::class, 'update'])->name('admin.classes.update');

  Route::post('/permission', [PermissionController::class, 'store'])->name('admin.permissions.store');
Route::post('/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
 Route::post('/permission/{permission}/update', [PermissionController::class, 'update'])->name('admin.permissions.update');
Route::delete('permissions', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');
Route::post('permission/{permission}/edit', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');

  Route::post('/resourceTypes', [ResourceTypeController::class, 'store'])->name('admin.resource_types.store');
Route::post('/resourceTypes/create', [ResourceTypeController::class, 'create'])->name('admin.resource_types.create');
 Route::post('/resourceTypes/{resourceType}/update', [ResourceTypeController::class, 'update'])->name('admin.resource_types.update');
Route::delete('resourceTypes', [ResourceTypeController::class, 'destroy'])->name('admin.resource_types.destroy');
Route::put('resourceTypes/{id}', [ResourceTypeController::class, 'edit'])->name('admin.resource_types.edit');
Route::get('/resourceTypes', [ResourceTypeController::class, 'index'])->name('admin.resource_types.index');

 Route::post('/groups', [GroupController::class, 'store'])->name('admin.groups.store');
Route::delete('groups', [GroupController::class, 'destroy'])->name('admin.groups.destroy');
Route::put('groups/{id}', [GroupController::class, 'edit'])->name('admin.groups.edit');
Route::post('group/{group}', [GroupController::class, 'update'])->name('admin.groups.update');
Route::delete('classes', [ClassesController::class, 'destroy'])->name('admin.classes.destroy');
Route::put('classes/{id}', [ClassesController::class, 'edit'])->name('admin.classes.edit');
// Other Toggles.  '/admin/classes/{id}'
Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
Route::patch('classes/{classes}/toggle-active', [ClassesController::class, 'toggleActive'])->name('admin.toggle-active');
Route::get('classes/create', [ClassesController::class, 'create'])->name('admin.classes.create');
Route::get('/classes', [ClassesController::class, 'index'])->name('admin.classes.index');
  Route::get('/groups', [GroupController::class, 'index'])->name('admin.groups.index');
  Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
  
  Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::post('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
 Route::post('/role/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('roles', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
Route::put('roles/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');


  Route::get('/privileges', [PrivilegeController::class, 'index'])->name('admin.privileges.index');

  Route::post('/privileges', [PrivilegeController::class, 'store'])->name('admin.privileges.store');
Route::post('/privileges/create', [PrivilegeController::class, 'create'])->name('admin.privileges.create');
 Route::post('/privilege/{privilege}', [PrivilegeController::class, 'update'])->name('admin.privileges.update');
Route::delete('privileges', [PrivilegeController::class, 'destroy'])->name('admin.privileges.destroy');
Route::put('privileges/{id}', [PrivilegeController::class, 'edit'])->name('admin.privileges.edit');
// Route::get('/privileges', [PrivilegeController::class, 'index'])->name('admin.privileges.index');


    Route::post('/classes', [ClassesController::class, 'store'])->name('admin.classes.store');
      Route::get('/resource_types', [ResourceTypeController::class, 'index'])->name('admin.resource_types.index');
       Route::get('/resources', [ResourceController::class, 'index'])->name('admin.resources.index');
  Route::post('/groups/create', [GroupController::class, 'create'])->name('admin.groups.create');
Route::patch('groups/{group}/toggle-active', [GroupController::class, 'toggleActive'])->name('groups.toggle-active');

Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::resource('resources', \App\Http\Controllers\Admin\ResourceController::class);
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'customrole:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

});



Route::prefix('data-mapper')->middleware(['auth', 'IsAdmin'])->prefix('admin')->group(function () {

Route::get('/getColumns', [DataMapperController::class, 'getColumns'])->name('data_mapper.getColumns');

    Route::get('/', [DataMapperController::class, 'index'])->name('data_mapper.index');
    Route::post('/fetch-api', [DataMapperController::class, 'fetchApi'])->name('data_mapper.fetch_api');
    Route::post('/save-mapping', [DataMapperController::class, 'saveMapping'])->name('data_mapper.save_mapping');
    Route::delete('/delete-data', [DataMapperController::class, 'deleteData'])->name('data_mapper.delete_data');
});

Route::middleware(['auth', 'IsAdmin'])->prefix('admin')->group(function () {
    Route::get('/resource-access', [ResourceAccessController::class, 'index'])->name('admin.resource-access.index');
    Route::post('/resource-access', [ResourceAccessController::class, 'store'])->name('admin.resource-access.store');
    Route::delete('/resource-access/{resourceAccess}', [ResourceAccessController::class, 'destroy'])->name('admin.resource-access.destroy');
});
// Student Routes (with middleware)
Route::prefix('student')->middleware(['auth', 'IsStudent'])->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
    Route::get('/resources', [StudentResource::class, 'index'])->name('student.resources');
    Route::get('/student/resources/{id}', [StudentResource::class, 'show'])->name('student.resources.show');
  //  Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
Route::get('/student/resources/{id}', [StudentDashboardController::class, 'showResource'])->name('student.resource.show');

//Route::prefix('student')->middleware(['auth','IsStudent'])->group(function () {
  //Route::post('/dashboards', [\App\Http\Controllers\Student\StudentDashboardController::class, 'show'])->name('student.dashboards');
   // Route::get('/dashboard', [\App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('student.dashboard');
//});
//Route::prefix('student')->middleware(['auth','IsStudent'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/resource/{id}', [StudentDashboardController::class, 'showResource'])->name('student.resource.show');
//});
});


// Route::prefix('admin')->middleware('auth')->group(function () {
//     Route::post('/settings/update-logo', [SettingsController::class, 'updateLogo'])->name('settings.update-logo');
//     // Other routes (dashboard, data-handler, etc.)
// });
// use App\Http\Controllers\Admin\SettingsController;

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/settings/update-logo', [SettingsController::class, 'updateLogo'])->name('settings.update-logo');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/data-handler', [DataHandlerController::class, 'index'])->name('data-handler');
});
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
// routes/web.php
Route::get('/proxy-users', function () {
    return Http::get('https://usersms.institutionconnect.com/usersms/users')->body();
});
Route::get('/forgot-password', [PasswordResetController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
 require __DIR__.'/auth.php';
