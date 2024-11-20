<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

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

// Home
Route::redirect('/', '/home');

Route::get('/home', [HomeController::class, 'index'])->name('pages.home');

Route::get('/events/category={id}', [EventController::class, 'showByCategory'])->name('events.category');

Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');

// Events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('v');
    Route::get('/events/{id}', 'show')->name('events.show');
});



// API
Route::controller(EventController::class)->group(function () {
    Route::put('/api/events', 'create');
    Route::delete('/api/events/{event_id}', 'delete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Users
Route::controller(UserController::class)->group(function () {
    // Show user profile (R203)
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');
    // Edit profile form (R204) and action (R205)
    Route::get('/user/{id}/edit', [UserController::class, 'editForm'])->name('user.edit.form');
    Route::post('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit.action');
    // Delete account (R206)
    Route::post('/user/{id}/delete_account', 'deleteAccount')->where('id', '[0-9]+')->name('user.delete');
    // Change password form (R207) and action (R208)
    Route::get('/user/{id}/change_password', 'changePasswordForm')->where('id', '[0-9]+')->name('user.change_password.form');
    Route::post('/user/{id}/change_password', 'changePassword')->name('user.change_password.action');
    // Block user (R209) and unblock user (R210)
    Route::post('/user/{id}/block_user', 'block')->where('id', '[0-9]+')->name('user.block');
    Route::post('/user/{id}/unblock_user', 'unblock')->where('id', '[0-9]+')->name('user.unblock');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/{id}/edit', [UserController::class, 'editForm'])->name('user.edit.form');
    Route::post('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit.action');
});