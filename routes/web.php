<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Models\DailyContent;
use App\Models\Level;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
    
    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::get('/friends/add', [FriendController::class, 'search'])->name('friends.add');
    Route::post('/friends/add/{id}', [FriendController::class, 'store'])->name('friends.store');
    Route::post('/friends/accept/{id}', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{id}', [FriendController::class, 'destroy'])->name('friends.destroy');
    
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard'); 

    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');
    Route::post('/quiz', [QuizController::class, 'store'])->name('quiz.store');
    Route::get('/content/{dailyContent}', [HomeController::class, 'showContent'])->name('content.show');
    Route::post('/content/{dailyContent}/comment', [HomeController::class, 'storeComment'])->name('content.comment');
    Route::post('/content/{dailyContent}/seen', [HomeController::class, 'markAsSeen'])->name('content.seen');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        Route::get('/themes', [AdminController::class, 'themes'])->name('admin.themes.index');
        Route::post('/themes', [AdminController::class, 'storeTheme'])->name('admin.themes.store');
        Route::delete('/themes/{dailyContent}', [AdminController::class, 'destroyTheme'])->name('admin.themes.destroy');
        
        Route::get('/contents', [AdminController::class, 'contents'])->name('admin.contents.index');
        Route::post('/contents', [AdminController::class, 'storeContent'])->name('admin.contents.store');
        Route::patch('/contents/{dailyContent}', [AdminController::class, 'updateContent'])->name('admin.contents.update');
        Route::delete('/contents/{dailyContent}', [AdminController::class, 'destroyContent'])->name('admin.contents.destroy');
        
        Route::get('/content/{dailyContent}/quiz', [AdminController::class, 'manageQuiz'])->name('admin.quiz.manage');
        Route::post('/content/{dailyContent}/quiz', [AdminController::class, 'updateQuiz'])->name('admin.quiz.update');

        Route::get('/ads', [AdminController::class, 'ads'])->name('admin.ads.index');
        Route::post('/ads', [AdminController::class, 'storeAd'])->name('admin.ads.store');
        Route::patch('/ads/{ad}', [AdminController::class, 'updateAd'])->name('admin.ads.update');
        Route::delete('/ads/{ad}', [AdminController::class, 'destroyAd'])->name('admin.ads.destroy');
    });
});