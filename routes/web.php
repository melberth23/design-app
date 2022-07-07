<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

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

Route::get('/payment-success', [App\Http\Controllers\PaymentsController::class, 'payment'])->name('payment');
Route::get('/upgrade-payment-success', [App\Http\Controllers\PaymentsController::class, 'upgradepayment'])->name('upgrade.payment');
Route::get('/change-payment-success', [App\Http\Controllers\PaymentsController::class, 'changepayment'])->name('change.payment');
Route::get('/account/verify/{token}', [App\Http\Controllers\AccountController::class, 'verify'])->name('user.verify'); 
Route::post('/account/resend-code', [App\Http\Controllers\AccountController::class, 'resendCode'])->name('user.resendcode'); 
Route::post('/account/check', [App\Http\Controllers\AccountController::class, 'checkTokenAccount'])->name('user.check');

Route::get('/admin-login', [App\Http\Controllers\Auth\AuthenticateController::class, 'getAdminLogin'])->name('adminlogin');
Route::post('/userlogin', [App\Http\Controllers\Auth\AuthenticateController::class, 'authenticate'])->name('userlogin');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sedResetPasswordEmail'])->name('forgot.password');

Route::get('/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadBrandFile'])->name('download');
Route::get('/request/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadRequestFile'])->name('request.download');
Route::get('/comment/download/{asset}', [App\Http\Controllers\DownloadFileController::class, 'downloadCommentFile'])->name('comment.download');
Route::get('/generate-invoice/{invoice}', [App\Http\Controllers\AccountController::class, 'generateInvoicePDF'])->name('generate.invoice');
Route::get('/view-invoice/{invoice}', [App\Http\Controllers\AccountController::class, 'viewInvoicePDF'])->name('view.invoice');
Route::post('/send-invoice', [App\Http\Controllers\AccountController::class, 'sendInvoicePDF'])->name('send.invoice');

Route::get('/search', [App\Http\Controllers\RequestsController::class, 'searchRequests'])->name('search');

Route::get('/account/plan', [App\Http\Controllers\AccountController::class, 'plan'])->middleware(['auth'])->name('user.plan'); 
Route::post('/account/add-plan', [App\Http\Controllers\AccountController::class, 'addplan'])->middleware(['auth'])->name('user.addplan'); 

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth', 'is_verify_email'])->name('dashboard');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware(['auth', 'is_verify_email'])->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/security', [HomeController::class, 'securityProfile'])->name('security');
    Route::get('/delete-account', [HomeController::class, 'delete'])->name('delete.account');
    Route::get('/upgrade', [HomeController::class, 'upgrade'])->name('upgrade');
    Route::get('/subscription', [HomeController::class, 'subscription'])->name('subscription');
    Route::get('/invoices', [HomeController::class, 'invoices'])->name('invoices');
    Route::get('/payment-methods', [HomeController::class, 'paymentmethods'])->name('paymentmethods');
    Route::get('/change-card', [HomeController::class, 'changecard'])->name('changecard');
    Route::get('/notifications', [HomeController::class, 'notifications'])->name('notifications');
    Route::post('/updateprofileimage', [HomeController::class, 'updateProfileImage'])->name('updateprofileimage');
    Route::post('/upgradeplan', [HomeController::class, 'upgradeplan'])->name('upgradeplan');
    Route::post('/addplan', [HomeController::class, 'addplan'])->name('addplan');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/cancel', [HomeController::class, 'cancel'])->name('cancel');
    Route::post('/emailverify', [HomeController::class, 'emailverifyProfile'])->name('emailverify');
    Route::post('/phoneverify', [HomeController::class, 'phoneverifyProfile'])->name('phoneverify');
    Route::post('/resendcode', [HomeController::class, 'resendcodeProfile'])->name('resendcode');
    Route::post('/delete', [HomeController::class, 'deleteAccount'])->name('delete');
    Route::post('/confirm-delete', [HomeController::class, 'confirmDeleteAccount'])->name('confirm.delete');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

/* Normal User Dashboard */

// Brands 
Route::middleware(['auth', 'is_verify_email'])->prefix('brands')->name('brand.')->group(function(){
    Route::get('/', [App\Http\Controllers\BrandController::class, 'index'])->name('index');
    Route::get('/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'index'])->name('index.sort');
    Route::get('/drafts', [App\Http\Controllers\BrandController::class, 'drafts'])->name('drafts');
    Route::get('/drafts/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'drafts'])->name('drafts.sort');
    Route::get('/archived', [App\Http\Controllers\BrandController::class, 'archived'])->name('archived');
    Route::get('/archived/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'archived'])->name('archived.sort');
    Route::get('/view/{brand}', [App\Http\Controllers\BrandController::class, 'view'])->name('view');
    Route::get('/create', [App\Http\Controllers\BrandController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\BrandController::class, 'store'])->name('store');
    Route::get('/edit/{section}/{brand}', [App\Http\Controllers\BrandController::class, 'edit'])->name('edit');
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
    Route::get('/progress', [App\Http\Controllers\RequestsController::class, 'progress'])->name('progress');
    Route::get('/review', [App\Http\Controllers\RequestsController::class, 'review'])->name('review');
    Route::get('/delivered', [App\Http\Controllers\RequestsController::class, 'delivered'])->name('delivered');
    Route::get('/draft', [App\Http\Controllers\RequestsController::class, 'draft'])->name('draft');
    Route::get('/create', [App\Http\Controllers\RequestsController::class, 'create'])->name('create');
    Route::get('/request-type/{type}', [App\Http\Controllers\RequestsController::class, 'request_type'])->name('requesttype');
    Route::post('/store', [App\Http\Controllers\RequestsController::class, 'store'])->name('store');
    Route::post('/dimensions', [App\Http\Controllers\RequestsController::class, 'getDimensions'])->name('dimensions');
    Route::get('/edit/{requests}', [App\Http\Controllers\RequestsController::class, 'edit'])->name('edit');
    Route::get('/comment/{requests}', [App\Http\Controllers\RequestsController::class, 'comment'])->name('comment');
    Route::get('/files/{requests}', [App\Http\Controllers\RequestsController::class, 'files'])->name('files');
    Route::post('/user-comment', [App\Http\Controllers\RequestsController::class, 'addComment'])->name('addcomment');
    Route::put('/update/{requests}', [App\Http\Controllers\RequestsController::class, 'update'])->name('update');
    Route::post('/delete', [App\Http\Controllers\RequestsController::class, 'delete'])->name('destroy');
    Route::post('/leavereview', [App\Http\Controllers\RequestsController::class, 'leavereview'])->name('leavereview');
    Route::post('/delete-media', [App\Http\Controllers\RequestsController::class, 'deleteAsset'])->name('destroyassets');
    Route::get('/update/status/{request_id}/{status}', [App\Http\Controllers\RequestsController::class, 'updateStatus'])->name('status');
    Route::post('/fileupload', [App\Http\Controllers\RequestsController::class, 'fileupload'])->name('fileupload');
});

/* Designer Dashboard */
Route::middleware(['auth'])->prefix('designers')->name('designer.')->group(function(){
    Route::get('/', [App\Http\Controllers\Designer\DesignersController::class, 'index'])->name('index');
    Route::get('/queue', [App\Http\Controllers\Designer\DesignersController::class, 'queue'])->name('queue');
    Route::get('/progress', [App\Http\Controllers\Designer\DesignersController::class, 'progress'])->name('progress');
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

// Admin Subscribers 
Route::middleware('auth')->prefix('admin/subscribers')->name('subscribers.')->group(function(){
    Route::get('/', [App\Http\Controllers\Admin\PaymentsController::class, 'index'])->name('index');
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

// Admin Brand Profiles 
Route::middleware('auth')->prefix('admin/brand')->name('adminbrand.')->group(function(){
    Route::get('/', [App\Http\Controllers\BrandController::class, 'index'])->name('index');
    Route::get('/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'index'])->name('index.sort');
    Route::get('/drafts', [App\Http\Controllers\BrandController::class, 'drafts'])->name('drafts');
    Route::get('/drafts/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'drafts'])->name('drafts.sort');
    Route::get('/archived', [App\Http\Controllers\BrandController::class, 'archived'])->name('archived');
    Route::get('/archived/sort/{type}/{sort}', [App\Http\Controllers\BrandController::class, 'archived'])->name('archived.sort');
    Route::get('/view/{brand}', [App\Http\Controllers\BrandController::class, 'view'])->name('view');
    Route::get('/create', [App\Http\Controllers\BrandController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\BrandController::class, 'store'])->name('store');
    Route::get('/edit/{section}/{brand}', [App\Http\Controllers\BrandController::class, 'edit'])->name('edit');
    Route::put('/update/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('update');
    Route::delete('/delete/{brand}', [App\Http\Controllers\BrandController::class, 'delete'])->name('destroy');
    Route::post('/delete-assets', [App\Http\Controllers\BrandController::class, 'deleteAsset'])->name('destroyassets');
    Route::get('/update/status/{brand_id}/{status}', [App\Http\Controllers\BrandController::class, 'updateStatus'])->name('status');
});