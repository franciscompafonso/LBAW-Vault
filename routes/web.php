<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::get('/home', function () {
    return view('pages.home'); // this points to resources/views/home.blade.php
});

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show')->name('cards.show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
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
    Route::get('/user/{id}/edit', 'editForm')->where('id', '[0-9]+')->name('user.edit.form');
    Route::post('/user/{id}/edit', 'edit')->name('user.edit.action');
    // Delete account (R206)
    Route::post('/user/{id}/delete_account', 'deleteAccount')->where('id', '[0-9]+')->name('user.delete');
    // Change password form (R207) and action (R208)
    Route::get('/user/{id}/change_password', 'changePasswordForm')->where('id', '[0-9]+')->name('user.change_password.form');
    Route::post('/user/{id}/change_password', 'changePassword')->name('user.change_password.action');
    // Block user (R209) and unblock user (R210)
    Route::post('/user/{id}/block_user', 'block')->where('id', '[0-9]+')->name('user.block');
    Route::post('/user/{id}/unblock_user', 'unblock')->where('id', '[0-9]+')->name('user.unblock');
});