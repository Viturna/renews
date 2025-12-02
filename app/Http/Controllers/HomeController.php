<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailyContent;
use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            
            $seenContentIds = DB::table('user_progress')->where('user_id', $userId)->pluck('daily_content_id');

            $articles = DailyContent::with('theme')
                ->whereDate('publish_date', '<=', Carbon::today())
                ->whereNotIn('id', $seenContentIds)
                ->orderBy('publish_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($article) {
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $article->video_url, $matches)) {
                        $article->thumbnail = "https://img.youtube.com/vi/" . $matches[1] . "/maxresdefault.jpg";
                    } else {
                        $article->thumbnail = "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158";
                    }
                    return $article;
                });
            
            $currentLevel = $user->level;
            $currentLevelNum = $currentLevel ? $currentLevel->level_number : 1;
            $currentXp = $user->current_xp;

            $nextLevel = Level::where('level_number', '>', $currentLevelNum)
                ->orderBy('level_number', 'asc')
                ->first();

            $currentLevelThreshold = $currentLevel ? $currentLevel->xp_threshold : 0;
            $nextLevelThreshold = $nextLevel ? $nextLevel->xp_threshold : ($currentXp * 1.5); // Fallback si niveau max

            $levelRange = $nextLevelThreshold - $currentLevelThreshold;
            if ($levelRange <= 0) $levelRange = 1; // Éviter division par zéro
            
            $progressPercent = (($currentXp - $currentLevelThreshold) / $levelRange) * 100;
            $progressPercent = min(100, max(0, $progressPercent)); // Borner entre 0 et 100

            $totalUsers = User::count();
            $usersWithMoreXp = User::where('current_xp', '>', $currentXp)->count();
            $rankTop = $totalUsers > 0 ? round((($usersWithMoreXp + 1) / $totalUsers) * 100) : 1;

            $stats = [
                'level' => $currentLevelNum,
                'current_xp' => $currentXp,
                'next_level_xp' => $nextLevelThreshold,
                'progress_percent' => $progressPercent,
                'rank_top' => $rankTop,
            ];

            return view('dashboard', [
                'articles' => $articles, 
                'stats' => $stats
            ]);
        }
        
        return view('welcome');
    }

    public function markAsSeen($id)
    {
        $user = Auth::user();
        
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $user->id, 'daily_content_id' => $id],
            ['updated_at' => now()]
        );

        // Logique Flammes
        $today = Carbon::today();
        $lastStreakDate = $user->last_streak_at ? $user->last_streak_at->startOfDay() : null;

        if (!$lastStreakDate) {
            $user->update(['current_streak' => 1, 'last_streak_at' => now()]);
        } elseif ($lastStreakDate->diffInDays($today) == 1) {
            $user->increment('current_streak');
            $user->update(['last_streak_at' => now()]);
        } elseif ($lastStreakDate->diffInDays($today) > 1) {
            $user->update(['current_streak' => 1, 'last_streak_at' => now()]);
        }

        return response()->json(['status' => 'success', 'current_streak' => $user->current_streak]);
    }

    public function showContent(DailyContent $dailyContent)
    {
        $dailyContent->load(['comments.user', 'comments.replies.user']);

        $embedUrl = $dailyContent->video_url;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $dailyContent->video_url, $matches)) {
            $embedUrl = "https://www.youtube.com/embed/" . $matches[1] . "?autoplay=1&rel=0";
        }

        return view('content.show', compact('dailyContent', 'embedUrl'));
    }

    public function storeComment(Request $request, DailyContent $dailyContent)
    {
        if ($request->has('reply_body')) {
            $request->validate(['reply_body' => 'required|string|max:1000', 'parent_id' => 'required|exists:comments,id']);
            $body = $request->reply_body;
            $parentId = $request->parent_id;
        } else {
            $request->validate(['body' => 'required|string|max:1000']);
            $body = $request->body;
            $parentId = null;
        }

        $dailyContent->comments()->create([
            'user_id' => Auth::id(),
            'body' => $body,
            'parent_id' => $parentId,
        ]);

        return back()->with('success', 'Message publié !');
    }
}