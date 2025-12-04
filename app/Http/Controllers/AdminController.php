<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;
use App\Models\DailyContent;
use App\Models\User;
use App\Models\Level;
use Illuminate\Database\UniqueConstraintViolationException;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_contents' => DailyContent::count(),
            'total_themes' => Theme::count(),
            'today_content' => DailyContent::whereDate('publish_date', today())->exists(),
        ];

        $lastUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'lastUsers'));
    }

    // --- GESTION UTILISATEURS ---
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->with('level')->orderBy('created_at', 'desc')->paginate(20);
        $levels = Level::all(); 

        return view('admin.users', compact('users', 'levels'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_xp' => 'required|integer|min:0',
            'current_level_id' => 'required|exists:levels,id',
        ]);

        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour !');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Action impossible sur soi-même.']);
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
    // ----------------------------

    public function themes()
    {
        $themes = Theme::withCount('dailyContents')->get();
        return view('admin.themes', compact('themes'));
    }

    public function contents()
    {
        $contents = DailyContent::with('theme')
            ->orderBy('publish_date', 'desc')
            ->paginate(15);
            
        $themes = Theme::all();

        return view('admin.contents', compact('contents', 'themes'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes',
            'icon_url' => 'nullable|string',
        ]);

        Theme::create($request->all());

        return back()->with('success', 'Thème ajouté !');
    }

    public function storeContent(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'publish_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $publishDate = \Carbon\Carbon::parse($request->publish_date);
        $unlockDate = $publishDate->copy()->addDay()->setTime(8, 0, 0);

        try {
            DailyContent::create([
                'theme_id' => $request->theme_id,
                'title' => $request->title,
                'video_url' => $request->video_url,
                'description' => $request->description,
                'publish_date' => $request->publish_date,
                'unlock_quiz_at' => $unlockDate,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return back()
                ->withErrors(['publish_date' => 'Date déjà prise.'])
                ->withInput();
        }

        return back()->with('success', 'Contenu planifié !');
    }

    public function manageQuiz(DailyContent $dailyContent)
    {
        $dailyContent->load('questions.answers');
        return view('admin.quiz_manager', compact('dailyContent'));
    }

    public function updateQuiz(Request $request, DailyContent $dailyContent)
    {
        $data = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.correct_index' => 'required|integer',
        ]);

        $dailyContent->questions()->delete();

        foreach ($data['questions'] as $qData) {
            $question = $dailyContent->questions()->create([
                'question_text' => $qData['text'],
                'points_value' => $qData['points'],
            ]);

            foreach ($qData['answers'] as $index => $aData) {
                $question->answers()->create([
                    'answer_text' => $aData['text'],
                    'is_correct' => ($index == $qData['correct_index']),
                ]);
            }
        }

        return redirect()->route('admin.contents.index')->with('success', 'Quiz mis à jour !');
    }
}