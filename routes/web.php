<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\SocialAuthController;
// Models
use App\Models\DailyContent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PAGE D'ACCUEIL (Swipe Deck / Landing) ---
Route::get('/', [HomeController::class, 'index'])->name('dashboard');


// --- ROUTES INVITÉS (Guest) ---
Route::middleware('guest')->group(function () {
    // Auth Classique
    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    
    // Auth Sociale
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
    
    // Password Reset
    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');
});


// --- ROUTES CONNECTÉS (Auth) ---
Route::middleware('auth')->group(function () {
    
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // 1. GESTION AMIS (Via FriendController)
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::get('/friends/add', [FriendController::class, 'search'])->name('friends.add');
    Route::post('/friends/add/{id}', [FriendController::class, 'store'])->name('friends.store');
    Route::post('/friends/accept/{id}', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{id}', [FriendController::class, 'destroy'])->name('friends.destroy');

    // 2. QUIZ (Via QuizController)
    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');

    // 3. CONTENU (VIDÉO, COMMENTAIRES, VU)
    Route::get('/content/{dailyContent}', [HomeController::class, 'showContent'])->name('content.show');
    Route::post('/content/{dailyContent}/comment', [HomeController::class, 'storeComment'])->name('content.comment');
    Route::post('/content/{dailyContent}/seen', [HomeController::class, 'markAsSeen'])->name('content.seen');

    // 4. PROFIL
    // Vue Visuelle (Design Gamification)
    Route::get('/profile', function () {
        $user = Auth::user();
        // Données simulées pour la maquette
        $stats = ['level' => 38, 'current_xp' => 85, 'next_level_xp' => 100, 'rank_top' => 10, 'trophies' => 4];
        return view('profile.show', compact('user', 'stats'));
    })->name('profile.show');

    // Paramètres du compte (Breeze)
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 5. ADMIN (Via AdminController)
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/themes', [AdminController::class, 'storeTheme'])->name('admin.themes.store');
        Route::post('/contents', [AdminController::class, 'storeContent'])->name('admin.contents.store');
        
        // Gestion des Quiz
        Route::get('/content/{dailyContent}/quiz', [AdminController::class, 'manageQuiz'])->name('admin.quiz.manage');
        Route::post('/content/{dailyContent}/quiz', [AdminController::class, 'updateQuiz'])->name('admin.quiz.update');
    });
});