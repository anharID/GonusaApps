<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\Admin\AppsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'index'])->name('login_page');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('admin')->group(function () {
        //Menu manajemen aksses
        Route::get('/user-access', [UserAccessController::class, 'index'])->name('user-access.index');
        Route::get('/user-access/data', [UserAccessController::class, 'getData'])->name('user-access.data');
        Route::post('/user-access', [UserAccessController::class, 'store'])->name('user-access.store');
        Route::delete('/user-access/{id}', [UserAccessController::class, 'destroy'])->name('user-access.destroy');
        Route::get('/user-access/{userId}/apps', [UserAccessController::class, 'getUserApps'])
            ->name('user-access.get-apps');

        //Menu manajemen aplikasi
        Route::get('/apps', [AppsController::class, 'index'])->name('apps.index');
        Route::get('/apps/data', [AppsController::class, 'getData'])->name('apps.data');
        Route::post('/apps', [AppsController::class, 'store'])->name('apps.store');
        Route::get('/apps/{id}/edit', [AppsController::class, 'edit'])->name('apps.edit');
        Route::put('/apps/{id}', [AppsController::class, 'update'])->name('apps.update');
        Route::delete('/apps/{id}', [AppsController::class, 'destroy'])->name('apps.destroy');

        //Menu manajemen user
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
