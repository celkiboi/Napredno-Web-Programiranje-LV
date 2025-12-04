<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hr'])) {
        session(['locale' => $locale]);
    }
    return back();
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => ['role:student']], function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/apply', [ApplicationController::class, 'store'])->name('applications.store');
        Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])
            ->name('applications.destroy');
    });

    // Teacher Routes
    Route::group(['middleware' => ['role:teacher']], function () {
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        
        // Route to accept student
        Route::post('/tasks/{task}/accept/{student}', [ApplicationController::class, 'accept'])
            ->name('tasks.accept');
    });



    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // This creates routes for: admin.users.index, create, store, edit, update, destroy
        Route::resource('users', UserController::class);
    });

    // Admin Routes
    Route::group(['middleware' => ['role:admin']], function () {
        // User management routes here
    });
});

require __DIR__.'/auth.php';
