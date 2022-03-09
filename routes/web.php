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

Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('home');
Route::get('/solutions', [App\Http\Controllers\PageController::class, 'index'])->name('solutions');
Route::get('/platform', [App\Http\Controllers\PageController::class, 'index'])->name('platform');
Route::get('/our-works', [App\Http\Controllers\PageController::class, 'index'])->name('ourworks');
Route::get('/resources', [App\Http\Controllers\PageController::class, 'index'])->name('resources');
Route::get('/plans', [App\Http\Controllers\PageController::class, 'plans'])->name('plans');

Auth::routes(['register' => false]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

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

// Blogs 
Route::middleware('auth')->prefix('admin/blogs')->name('blogs.')->group(function(){
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::post('/store', [BlogController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [BlogController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [BlogController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [BlogController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [BlogController::class, 'updateStatus'])->name('status');
});