<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UnblockRequestController;

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
Route::get('/about', function () { return view('pages.about');});
Route::get('/faqs', function () {return view('pages.faqs'); })->name('faqs');

Route::get('/home', [HomeController::class, 'index'])->name('pages.home');

Route::get('/events/search', [EventController::class, 'searchFiltered'])->name('events.search');
Route::get('/photos/{id}', [FileController::class, 'show'])->name('photos.show');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Registration routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/admin/logout', 'logout')->name('admin.logout');
});

Route::controller(MailController::class)->group(function () {
    Route::post('forgot-password/email', 'sendResetLink')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('password/reset', 'resetPassword')->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events', [EventController::class, 'index'])->name('v');
    Route::get('/events/{event}/survey', [EventController::class, 'showSurvey'])->name('events.survey');
    Route::post('/events/{event}/survey', [EventController::class, 'submitSurvey'])->name('events.submitSurvey');
    Route::post('/events/{event}/request', [EventController::class, 'requestToJoin'])->name('events.request');
    Route::post('/events/{event}/join', [EventController::class, 'joinEvent'])->name('events.join');
    Route::post('/events/{event}/leave', [EventController::class, 'leaveEvent'])->name('events.leave');

    Route::post('/invites/{invitesId}/decline', [InviteController::class, 'declineInvite'])->name('invites.decline');
    Route::post('/invites/{invitesId}/accept', [InviteController::class, 'acceptInvite'])->name('invites.accept');
    Route::post('/events/{event}/invite', [InviteController::class, 'sendInvite'])->name('events.invite');

    Route::post('/requests/{requestId}/decline', [JoinRequestController::class, 'declineRequest'])->name('requests.decline');
    Route::post('/requests/{requestId}/accept', [JoinRequestController::class, 'acceptRequest'])->name('requests.accept');

    Route::post('/comment/like', [CommentFeedbackController::class, 'like'])->name('comment.like');
    Route::post('/comment/dislike', [CommentFeedbackController::class, 'dislike'])->name('comment.dislike');


    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    Route::post('/events/{event}/polls', [PollController::class, 'store'])->name('polls.store');
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/{id}', 'show')->name('user.profile');
        Route::get('/user/{id}/edit', 'editForm')->name('user.edit.form');
        Route::post('/user/{id}/edit', 'edit')->name('user.edit.action');
        Route::delete('/user/{id}/delete', 'deleteAccount')->name('user.delete');
        Route::get('/user/{id}/change_password', 'changePasswordForm')->where('id', '[0-9]+')->name('user.change_password.form');
        Route::post('/user/{id}/change_password', 'changePassword')->name('user.change_password.action');
        Route::post('/user/{id}/block_user', 'block')->where('id', '[0-9]+')->name('user.block');
        Route::post('/user/{id}/unblock_user', 'unblock')->where('id', '[0-9]+')->name('user.unblock');
        Route::get('/user/{id}/invites', [InviteController::class, 'showUserInvites'])->name('user.invites.sent');
        Route::delete('/invite/{id}', [InviteController::class, 'deleteInvite'])->name('invite.delete');
    });


    Route::get('/user/{id}/edit', [UserController::class, 'editForm'])->name('user.edit.form');
    Route::post('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit.action');
    Route::post('/notifications/dismiss', [NotificationController::class, 'dismissNotification']);
    Route::post('/notifications/dismiss-all', [NotificationController::class, 'dismissAllNotifications']);
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');

    // API
    Route::get('/api/notifications', [NotificationController::class, 'index']);

    Route::get('/unblock-request', [UnblockRequestController::class, 'create'])->name('unblock.request.create');
    Route::post('/unblock-request', [UnblockRequestController::class, 'store'])->name('unblock.request.store');
});
Route::get('/api/search', [EventController::class, 'search'])->name('search');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/user-management', [AdminController::class, 'listUsers'])->name('admin.user-management');
    Route::get('/admin/user-management/search-user', [AdminController::class, 'search'])->name('admin.search.user');
    Route::delete('/admin/user-management/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/user-management/block-user/{user}', [AdminController::class, 'blockUser'])->name('admin.block.user');
    Route::patch('/admin/user-management/unblock-user/{user}', [AdminController::class, 'unblockUser'])->name('admin.unblock.user');
    Route::get('/admin/create', [AdminController::class, 'showCreateAdminForm'])->name('admin.admins.create');
    Route::post('/admin/store', [AdminController::class, 'storeAdmin'])->name('admin.admins.store');
    Route::get('admin/user/{id}/edit', [AdminController::class, 'adminEditForm'])->name('admin.edit.form');
    Route::post('admin/user/{id}/edit', [AdminController::class, 'adminEdit'])->name('admin.edit.action');
    Route::get('/admin/unblock-requests', [UnblockRequestController::class, 'index'])->name('admin.unblock.requests');
    Route::post('/admin/unblock-requests/{id}/approve', [UnblockRequestController::class, 'approve'])->name('admin.unblock.requests.approve');
    Route::post('/admin/unblock-requests/{id}/reject', [UnblockRequestController::class, 'reject'])->name('admin.unblock.requests.reject');
});

Route::middleware(['auth:web,admin'])->group(function () {
    Route::post('/events/{event}/participators/{user}/kick', [EventController::class, 'kickParticipator'])->name('events.participators.kick');
    Route::delete('/comments/{id}', [CommentController::class, 'delete'])->name('comments.delete');
    Route::delete('/events/{id}', [EventController::class, 'deleteEvent'])->name('events.delete');
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::get('/events/{event}/survey/stats', [EventController::class, 'showSurveyStats'])->name('events.surveyStats');
});
