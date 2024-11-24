<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;

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

Route::get('/about', function () { return view('pages.about');});

Route::get('/search', [EventController::class, 'search'])->name('search');

Route::get('/events/category={id}', [EventController::class, 'showByCategory'])->name('events.category');

Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');

// Events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('v');
    Route::get('/events/{id}', 'show')->name('events.show');
});

Route::post('/events/{event}/join', [EventController::class, 'joinEvent'])->name('events.join');
Route::post('/events/{event}/delete', [EventController::class, 'deleteEvent'])->name('events.delete');
Route::post('/events/{event}/leave', [EventController::class, 'leaveEvent'])->name('events.leave');
Route::post('/events/{event}/participators/{user}/kick', [EventController::class, 'kickParticipator'])->name('events.participators.kick');

Route::get('/comments/{commentId}/photos', [PhotoController::class, 'getPhotosByComment']);
Route::post('/comments/{comment}/photos', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/photos/{id}', [PhotoController::class, 'show'])->name('photos.show');

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
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/dismiss', [NotificationController::class, 'dismissNotification'])->middleware('auth');
    Route::post('/notifications/dismiss-all', [NotificationController::class, 'dismissAllNotifications'])->middleware('auth');
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount']);
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Comments
Route::post('/comments/{comment}/delete', [CommentController::class, 'delete'])->name('comments.delete');

// Invite
Route::post('/events/{event}/invite', [InviteController::class, 'sendInvite'])->name('events.invite');

Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {

    // Route to dashboard (This route automatically redirects to the dashboard)
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    // Admin dashboard route
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // User management routes
    Route::get('/admin/user-management', [AdminController::class, 'listUsers'])->name('admin.user-management');
    Route::get('/admin/user-management/search-user', [AdminController::class, 'search'])->name('admin.search.user');
    Route::delete('/admin/user-management/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::patch('/admin/user-management/undelete-user/{user}', [AdminController::class, 'undeleteUser'])->name('admin.undelete.user');
    Route::post('/admin/user-management/block-user/{user}', [AdminController::class, 'blockUser'])->name('admin.block.user');
    Route::patch('/admin/user-management/unblock-user/{user}', [AdminController::class, 'unblockUser'])->name('admin.unblock.user');
});
