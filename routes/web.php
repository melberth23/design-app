<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('home');
// Route::get('/solutions', [App\Http\Controllers\PageController::class, 'index'])->name('solutions');
// Route::get('/platform', [App\Http\Controllers\PageController::class, 'index'])->name('platform');
// Route::get('/our-works', [App\Http\Controllers\PageController::class, 'index'])->name('ourworks');
// Route::get('/resources', [App\Http\Controllers\PageController::class, 'index'])->name('resources');
// Route::get('/plans', [App\Http\Controllers\PageController::class, 'plans'])->name('plans');
Route::get('/payment-success', [App\Http\Controllers\PaymentsController::class, 'payment'])->name('payment');
Route::get('/account/verify/{token}', [App\Http\Controllers\AccountController::class, 'verify'])->name('user.verify'); 
Route::post('/account/check', [App\Http\Controllers\AccountController::class, 'checkTokenAccount'])->name('user.check');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::get('/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadBrandFile'])->name('download');
Route::get('/request/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadRequestFile'])->name('request.download');
Route::get('/comment/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadCommentFile'])->name('comment.download');

Route::get('/account/plan', [App\Http\Controllers\AccountController::class, 'plan'])->middleware(['auth'])->name('user.plan'); 
Route::post('/account/add-plan', [App\Http\Controllers\AccountController::class, 'addplan'])->middleware(['auth'])->name('user.addplan'); 

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth', 'is_verify_email'])->name('dashboard');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware(['auth', 'is_verify_email'])->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

/* Normal User Dashboard */

// Brands 
Route::middleware(['auth', 'is_verify_email'])->prefix('brands')->name('brand.')->group(function(){
    Route::get('/', [App\Http\Controllers\BrandController::class, 'index'])->name('index');
    Route::get('/view/{brand}', [App\Http\Controllers\BrandController::class, 'view'])->name('view');
    Route::get('/create', [App\Http\Controllers\BrandController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\BrandController::class, 'store'])->name('store');
    Route::get('/edit/{brand}', [App\Http\Controllers\BrandController::class, 'edit'])->name('edit');
    Route::put('/update/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('update');
    Route::delete('/delete/{brand}', [App\Http\Controllers\BrandController::class, 'delete'])->name('destroy');
    Route::post('/delete-assets', [App\Http\Controllers\BrandController::class, 'deleteAsset'])->name('destroyassets');
    Route::get('/update/status/{brand_id}/{status}', [App\Http\Controllers\BrandController::class, 'updateStatus'])->name('status');
});

// Requests 
Route::middleware(['auth', 'is_verify_email'])->prefix('requests')->name('request.')->group(function(){
    Route::get('/', [App\Http\Controllers\RequestsController::class, 'index'])->name('index');
    Route::get('/view/{requests}', [App\Http\Controllers\RequestsController::class, 'view'])->name('view');
    Route::get('/queue', [App\Http\Controllers\RequestsController::class, 'queue'])->name('queue');
    Route::get('/review', [App\Http\Controllers\RequestsController::class, 'review'])->name('review');
    Route::get('/delivered', [App\Http\Controllers\RequestsController::class, 'delivered'])->name('delivered');
    Route::get('/create', [App\Http\Controllers\RequestsController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\RequestsController::class, 'store'])->name('store');
    Route::get('/edit/{requests}', [App\Http\Controllers\RequestsController::class, 'edit'])->name('edit');
    Route::get('/comment/{requests}', [App\Http\Controllers\RequestsController::class, 'comment'])->name('comment');
    Route::post('/user-comment', [App\Http\Controllers\RequestsController::class, 'addComment'])->name('addcomment');
    Route::put('/update/{requests}', [App\Http\Controllers\RequestsController::class, 'update'])->name('update');
    Route::delete('/delete/{requests}', [App\Http\Controllers\RequestsController::class, 'delete'])->name('destroy');
    Route::post('/delete-media', [App\Http\Controllers\RequestsController::class, 'deleteAsset'])->name('destroyassets');
    Route::get('/update/status/{request_id}/{status}', [App\Http\Controllers\RequestsController::class, 'updateStatus'])->name('status');
});

/* Designer Dashboard */
Route::middleware(['auth'])->prefix('designers')->name('designer.')->group(function(){
    Route::get('/', [App\Http\Controllers\Designer\DesignersController::class, 'index'])->name('index');
    Route::get('/queue', [App\Http\Controllers\Designer\DesignersController::class, 'queue'])->name('queue');
    Route::get('/review', [App\Http\Controllers\Designer\DesignersController::class, 'review'])->name('review');
    Route::get('/completed', [App\Http\Controllers\Designer\DesignersController::class, 'delivered'])->name('completed');
    Route::get('/designer-view/{requests}', [App\Http\Controllers\Designer\DesignersController::class, 'view'])->name('view');
    Route::get('/designer-comment/{requests}', [App\Http\Controllers\Designer\DesignersController::class, 'comment'])->name('comment');
    Route::get('/designer-update/status/{request_id}/{status}', [App\Http\Controllers\Designer\DesignersController::class, 'updateStatus'])->name('status');
    Route::post('/add-comment', [App\Http\Controllers\Designer\DesignersController::class, 'addComment'])->name('addcomment');
    Route::post('/add-file-review', [App\Http\Controllers\Designer\DesignersController::class, 'addFileReview'])->name('addfilereview');
});

/* Super Admin Dashboard */

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users 
Route::middleware('auth')->prefix('admin/users')->name('users.')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');

    
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');

});

// Request Types
Route::middleware('auth')->prefix('admin/requesttypes')->name('requesttypes.')->group(function(){
    Route::get('/', [App\Http\Controllers\Admin\RequestTypesController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\RequestTypesController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Admin\RequestTypesController::class, 'store'])->name('store');
    Route::get('/edit/{requesttype}', [App\Http\Controllers\Admin\RequestTypesController::class, 'edit'])->name('edit');
    Route::put('/update/{requesttype}', [App\Http\Controllers\Admin\RequestTypesController::class, 'update'])->name('update');
    Route::delete('/delete/{requesttype}', [App\Http\Controllers\Admin\RequestTypesController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{request_id}/{status}', [App\Http\Controllers\Admin\RequestTypesController::class, 'updateStatus'])->name('status');
});

// Admin Requests 
Route::middleware('auth')->prefix('admin/requests')->name('adminrequest.')->group(function(){
    Route::get('/', [App\Http\Controllers\Admin\RequestsAdminController::class, 'index'])->name('index');
    Route::get('/view/{requests}', [App\Http\Controllers\Admin\RequestsAdminController::class, 'view'])->name('view');
    Route::get('/queue', [App\Http\Controllers\Admin\RequestsAdminController::class, 'queue'])->name('queue');
    Route::get('/review', [App\Http\Controllers\Admin\RequestsAdminController::class, 'review'])->name('review');
    Route::get('/delivered', [App\Http\Controllers\Admin\RequestsAdminController::class, 'delivered'])->name('delivered');
    Route::get('/comment/{requests}', [App\Http\Controllers\Admin\RequestsAdminController::class, 'comment'])->name('comment');
    Route::post('/user-comment', [App\Http\Controllers\Admin\RequestsAdminController::class, 'addComment'])->name('addcomment');
    Route::delete('/delete/{requests}', [App\Http\Controllers\Admin\RequestsAdminController::class, 'delete'])->name('destroy');
    Route::post('/delete-media', [App\Http\Controllers\Admin\RequestsAdminController::class, 'deleteAsset'])->name('destroyassets');
    Route::get('/update/status/{request_id}/{status}', [App\Http\Controllers\Admin\RequestsAdminController::class, 'updateStatus'])->name('status');
});

// Admin Payments 
Route::middleware('auth')->prefix('admin/payments')->name('adminpayment.')->group(function(){
    Route::get('/', [App\Http\Controllers\Admin\PaymentsController::class, 'index'])->name('index');
    Route::get('/pending', [App\Http\Controllers\Admin\PaymentsController::class, 'pending'])->name('pending');
    Route::get('/completed', [App\Http\Controllers\Admin\PaymentsController::class, 'completed'])->name('completed');
});