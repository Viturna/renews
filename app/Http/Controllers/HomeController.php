<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailyContent;
use Carbon\Carbon;

class HomeController extends Controller
{
    // ... (Méthodes index, markAsSeen, showContent inchangées) ...
    public function index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
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

            return view('dashboard', ['articles' => $articles]);
        }
        return view('welcome');
    }

    public function markAsSeen($id)
    {
        $userId = Auth::id();
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId, 'daily_content_id' => $id],
            ['updated_at' => now()]
        );
        return response()->json(['status' => 'success']);
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

    // 👇 MÉTHODE MODIFIÉE POUR ÉVITER LES DOUBLONS 👇
    public function storeComment(Request $request, DailyContent $dailyContent)
    {
        // CAS 1 : C'est une RÉPONSE (le champ s'appelle 'reply_body')
        if ($request->has('reply_body')) {
            $request->validate([
                'reply_body' => 'required|string|max:1000',
                'parent_id'  => 'required|exists:comments,id', // Parent OBLIGATOIRE
            ]);
            
            $body = $request->reply_body;
            $parentId = $request->parent_id;
        } 
        // CAS 2 : C'est un COMMENTAIRE PRINCIPAL (le champ s'appelle 'body')
        else {
            $request->validate([
                'body' => 'required|string|max:1000',
            ]);
            
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