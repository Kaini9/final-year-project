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
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Feed
    Route::get('/dashboard', [\App\Http\Controllers\FeedController::class, 'index'])->name('dashboard');

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
});

require __DIR__.'/auth.php';
