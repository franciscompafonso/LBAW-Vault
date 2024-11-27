<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\PollController;

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
Route::get('/events/{event}/survey', [EventController::class, 'showSurvey'])->name('events.survey');
Route::post('/events/{event}/survey', [EventController::class, 'submitSurvey'])->name('events.submitSurvey');
Route::get('/events/{event}/survey/stats', [EventController::class, 'showSurveyStats'])->name('events.surveyStats');

Route::get('/faqs', function () {
    return view('pages.faqs'); 
})->name('faqs');

// Events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('v');
    Route::get('/events/{id}', 'show')->name('events.show');
});

Route::post('/requests/{requestId}/accept', [JoinRequestController::class, 'acceptRequest'])->name('requests.accept');
Route::post('/events/{event}/request', [EventController::class, 'requestToJoin'])->name('events.request');
Route::post('/events/{event}/join', [EventController::class, 'joinEvent'])->name('events.join');
Route::delete('/events/{id}', [EventController::class, 'deleteEvent'])->name('events.delete');

Route::post('/events/{event}/leave', [EventController::class, 'leaveEvent'])->name('events.leave');
Route::post('/events/{event}/participators/{user}/kick', [EventController::class, 'kickParticipator'])->name('events.participators.kick');

Route::get('/comments/{commentId}/photos', [PhotoController::class, 'getPhotosByComment']);
Route::post('/comments/{comment}/photos', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/photos/{id}', [PhotoController::class, 'show'])->name('photos.show');

Route::post('/comment/like', [CommentFeedbackController::class, 'like'])->name('comment.like');
Route::post('/comment/dislike', [CommentFeedbackController::class, 'dislike'])->name('comment.dislike');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

Route::post('/events/{event}/polls', [PollController::class, 'store'])->name('polls.store');
Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');

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
    Route::delete('/user/{id}/delete', [UserController::class, 'deleteAccount'])->name('user.delete');

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
Route::post('/invites/{invite}/accept', [InviteController::class, 'acceptInvite'])->name('invites.accept');

Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {
    
    Route::get('/admin/user-management', [AdminController::class, 'listUsers'])->name('admin.user-management');
    Route::get('/admin/user-management/search-user', [AdminController::class, 'search'])->name('admin.search.user');
    Route::delete('/admin/user-management/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/user-management/block-user/{user}', [AdminController::class, 'blockUser'])->name('admin.block.user');
    Route::patch('/admin/user-management/unblock-user/{user}', [AdminController::class, 'unblockUser'])->name('admin.unblock.user');
    Route::post('/admin/comments/delete/{comment}', [AdminController::class, 'deleteComment'])->name('admin.comment.delete');
    Route::get('/admin/create', [AdminController::class, 'showCreateAdminForm'])->name('admin.admins.create');
    Route::post('/admin/store', [AdminController::class, 'storeAdmin'])->name('admin.admins.store');

    Route::get('admin/user/{id}/edit', [AdminController::class, 'adminEditForm'])->name('admin.edit.form');
    Route::post('admin/user/{id}/edit', [AdminController::class, 'adminEdit'])->name('admin.edit.action');
    
});
