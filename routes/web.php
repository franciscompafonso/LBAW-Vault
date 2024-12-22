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
use App\Http\Controllers\GoogleController;

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

// Public Routes
Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index'])->name('pages.home'); //check
Route::get('/about', fn() => view('pages.about')); //check
Route::get('/faqs', fn() => view('pages.faqs'))->name('faqs'); //check

Route::get('/events/search', [SearchController::class, 'filteredSearch'])->name('events.search'); //check
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show'); //check
Route::get('/photos/{id}', [FileController::class, 'show'])->name('photos.show'); //check

// Guest-only Routes
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); 
    Route::post('/login', [LoginController::class, 'authenticate']); 

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); 
    Route::post('/register', [RegisterController::class, 'register']); 
});

// Logout Routes
Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); 

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/event/create', [EventController::class, 'create'])->name('event.create'); //check
    Route::post('/events', [EventController::class, 'store'])->name('events.store'); //check
    Route::get('/events', [EventController::class, 'index'])->name('myEvents'); //check
    Route::get('/events/{event}/survey', [EventController::class, 'showSurvey'])->name('events.survey'); //check
    Route::post('/events/{event}/survey', [EventController::class, 'submitSurvey'])->name('events.submitSurvey'); //check
    Route::post('/events/{event}/request', [EventController::class, 'requestToJoin'])->name('events.request'); //check
    Route::post('/events/{event}/join', [EventController::class, 'joinEvent'])->name('events.join'); //check
    Route::post('/events/{event}/leave', [EventController::class, 'leaveEvent'])->name('events.leave'); //check
    Route::post('/events/{event}/invite', [InviteController::class, 'sendInvite'])->name('events.invite'); //check
    Route::get('/events/{event}/participants', [EventController::class, 'getParticipants'])->name('events.participants'); //check
    Route::get('/events/{event}/update-comment-form', [EventController::class, 'updateCommentForm']); //check
    Route::get('/events/{event}/event-actions', [EventController::class, 'updateEventActions']); //check

    Route::post('/invites/{invitesId}/decline', [InviteController::class, 'declineInvite'])->name('invites.decline'); //check
    Route::post('/invites/{invitesId}/accept', [InviteController::class, 'acceptInvite'])->name('invites.accept'); //check
    
    Route::post('/requests/{requestId}/decline', [JoinRequestController::class, 'declineRequest'])->name('requests.decline'); //check
    Route::post('/requests/{requestId}/accept', [JoinRequestController::class, 'acceptRequest'])->name('requests.accept'); //check

    Route::post('/comment/{commentId}/like', [CommentFeedbackController::class, 'like'])->name('comment.like'); //check
    Route::post('/comment/{commentId}/dislike', [CommentFeedbackController::class, 'dislike'])->name('comment.dislike'); //check

    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply'); //check

    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote'); //check
    Route::post('/events/{event}/polls', [PollController::class, 'store'])->name('polls.store'); //check

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::delete('/user/{username}/delete', 'deleteAccount')->name('user.delete');  //check
        Route::get('/user/{username}/invites', [InviteController::class, 'showUserInvites'])->name('user.invites'); //check
        Route::delete('/invite/{id}', [InviteController::class, 'deleteInvite'])->name('invite.delete'); //check
    });

    Route::post('/notifications/{id}/dismiss', [NotificationController::class, 'dismissNotification']); //check
    Route::post('/notifications/dismiss-all', [NotificationController::class, 'dismissAllNotifications']); //check
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store'); //check

    // API
    Route::get('/api/notifications', [NotificationController::class, 'index']);

    Route::get('/unblock-request', [UnblockRequestController::class, 'create'])->name('unblock.request.create'); //check
    Route::post('/unblock-request', [UnblockRequestController::class, 'store'])->name('unblock.request.store'); //check
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/user-management', [AdminController::class, 'listUsers'])->name('admin.user-management');//check
    Route::get('/admin/user-management/search-user', [AdminController::class, 'search'])->name('admin.search.user');//check
    Route::delete('/admin/user-management/delete-user/{username}', [AdminController::class, 'deleteUser'])->name('admin.delete.user'); //check
    Route::post('/admin/user-management/block-user/{username}', [AdminController::class, 'blockUser'])->name('admin.block.user'); //check
    Route::patch('/admin/user-management/unblock-user/{username}', [AdminController::class, 'unblockUser'])->name('admin.unblock.user'); //check
    Route::get('/admin/create', [AdminController::class, 'showCreateAdminForm'])->name('admin.admins.create'); //check
    Route::post('/admin/store', [AdminController::class, 'storeAdmin'])->name('admin.admins.store'); //check
    Route::get('/admin/unblock-requests', [UnblockRequestController::class, 'index'])->name('admin.unblock.requests');//check
    Route::post('/admin/unblock-requests/{id}/approve', [UnblockRequestController::class, 'approve'])->name('admin.unblock.requests.approve');//check
    Route::post('/admin/unblock-requests/{id}/reject', [UnblockRequestController::class, 'reject'])->name('admin.unblock.requests.reject');//check

    Route::get('/admin/users/create', [RegisterController::class, 'showAdminCreationForm'])->name('admin.users.create'); //check
    Route::post('/admin/users/store', [RegisterController::class, 'register'])->name('admin.users.store'); //check
    
});

Route::middleware(['auth:web,admin'])->group(function () {
    Route::post('/events/{event}/participants/{username}/kick', [EventController::class, 'kickParticipator'])->name('events.participators.kick'); //check
    Route::delete('/comments/{id}', [CommentController::class, 'delete'])->name('comments.delete'); //check
    Route::delete('/events/{id}', [EventController::class, 'deleteEvent'])->name('events.delete'); //check
    Route::put('/events/{id}/cancel', [EventController::class, 'cancelEvent'])->name('events.cancel'); //check
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit'); //check
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update'); //check
    Route::get('/events/{event}/survey/stats', [EventController::class, 'showSurveyStats'])->name('events.surveyStats'); //check
    Route::get('/user/{username}', [UserController::class, 'show'])->name('user.profile');
    Route::get('/user/{username}/edit', [UserController::class, 'editForm'])->name('user.edit.form'); 
    Route::post('/user/{username}/edit', [UserController::class, 'edit'])->name('user.edit.action'); 
    Route::get('/events/{event}/comments', [EventController::class, 'getComments'])->name('comments.get'); //check
});

Route::controller(MailController::class)->group(function () {
    Route::post('forgot-password/email', 'sendResetLink')->name('password.email');
});
Route::get('/api/search', [SearchController::class, 'search'])->name('search');

// Google

Route::get('/api/search', [SearchController::class, 'search'])->name('search');
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('google-auth');
    Route::get('auth/google/call-back', 'callbackGoogle')->name('google-call-back');
});

// Password Reset Routes
Route::controller(ResetPasswordController::class)->group(function () { 
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request'); //check
    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('password/reset', 'resetPassword')->name('password.update');
});