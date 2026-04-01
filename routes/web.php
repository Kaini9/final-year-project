<?php

use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/portfolio', [ProfileController::class, 'updatePortfolio'])->name('profile.portfolio.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Feed
    Route::get('/dashboard', [\App\Http\Controllers\FeedController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Profiles
    Route::get('/u/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/u/{user}/follow', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('user.follow');
    
    // Posts
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
    
    // Interactions
    Route::post('/posts/{post}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comment', [\App\Http\Controllers\CommentController::class, 'store'])->name('posts.comment');

    // Job Marketplace
    Route::resource('jobs', \App\Http\Controllers\JobController::class);

    // Job Applications
    Route::get('/jobs/{job}/apply', [\App\Http\Controllers\JobApplicationController::class, 'create'])->name('job_applications.create');
    Route::post('/jobs/{job}/apply', [\App\Http\Controllers\JobApplicationController::class, 'store'])->name('job_applications.store');
    Route::get('/my-applications', [\App\Http\Controllers\JobApplicationController::class, 'myApplications'])->name('job_applications.mine');
    Route::patch('/applications/{jobApplication}', [\App\Http\Controllers\JobApplicationController::class, 'update'])->name('job_applications.update');
    Route::delete('/applications/{jobApplication}', [\App\Http\Controllers\JobApplicationController::class, 'destroy'])->name('job_applications.destroy');

    // Direct Messaging
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Verifications
    Route::post('/verifications', [\App\Http\Controllers\VerificationController::class, 'store'])->name('verifications.store');
    
    // Khalti Integrated Payment
    Route::get('/khalti/initiate/{verification}', [\App\Http\Controllers\KhaltiController::class, 'initiate'])->name('khalti.initiate');
    Route::get('/khalti/callback', [\App\Http\Controllers\KhaltiController::class, 'callback'])->name('khalti.callback');
    Route::get('/khalti/receipt/{verification}', [\App\Http\Controllers\KhaltiController::class, 'receipt'])->name('khalti.receipt');
    Route::get('/khalti/receipt/{verification}/download', [\App\Http\Controllers\KhaltiController::class, 'downloadReceipt'])->name('khalti.receipt.download');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Admin Panel
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Verifications Management
        Route::get('/verifications', [\App\Http\Controllers\AdminController::class, 'verifications'])->name('verifications');
        Route::post('/verifications/{verification}/approve', [\App\Http\Controllers\AdminController::class, 'approveVerification'])->name('verifications.approve');
        Route::post('/verifications/{verification}/reject', [\App\Http\Controllers\AdminController::class, 'rejectVerification'])->name('verifications.reject');
    });
});

require __DIR__.'/auth.php';
