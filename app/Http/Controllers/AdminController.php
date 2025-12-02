<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;
use App\Models\DailyContent;
use App\Models\User;
use App\Models\QuizQuestion;
use Illuminate\Database\UniqueConstraintViolationException; // Import de l'exception

class AdminController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'total_users' => User::count(),
            'total_contents' => DailyContent::count(),
            'total_themes' => Theme::count(),
            'today_content' => DailyContent::whereDate('publish_date', today())->exists() ? 'Oui' : 'Non',
        ];

        // Listes
        $themes = Theme::withCount('dailyContents')->get();
        $contents = DailyContent::with('theme')->orderBy('publish_date', 'desc')->paginate(10);
        $lastUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('themes', 'contents', 'stats', 'lastUsers'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes',
            'icon_url' => 'nullable|string',
        ]);

        Theme::create($request->all());

        return back()->with('success', 'Thème ajouté avec succès !');
    }

    public function storeContent(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            // On garde la validation, mais le try-catch est la sécurité ultime
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
            // En cas d'erreur de doublon SQL, on renvoie une erreur de validation propre
            return back()
                ->withErrors(['publish_date' => 'Un contenu est déjà planifié pour cette date.'])
                ->withInput();
        }

        return back()->with('success', 'Contenu planifié !');
    }

    // --- GESTION DES QUIZ ---

    public function manageQuiz(DailyContent $dailyContent)
    {
        // On charge les questions existantes avec leurs réponses pour les afficher dans le formulaire
        $dailyContent->load('questions.answers');
        return view('admin.quiz_manager', compact('dailyContent'));
    }

    public function updateQuiz(Request $request, DailyContent $dailyContent)
    {
        // Validation des données imbriquées (Questions -> Réponses)
        $data = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.correct_index' => 'required|integer', // L'index de la réponse correcte
        ]);

        // Pour simplifier la mise à jour, on supprime les anciennes questions et on recrée tout
        // (Méthode "Bourrin" mais efficace pour un admin simple)
        $dailyContent->questions()->delete();

        foreach ($data['questions'] as $qData) {
            // 1. Créer la question
            $question = $dailyContent->questions()->create([
                'question_text' => $qData['text'],
                'points_value' => $qData['points'],
            ]);

            // 2. Créer les réponses
            foreach ($qData['answers'] as $index => $aData) {
                $question->answers()->create([
                    'answer_text' => $aData['text'],
                    'is_correct' => ($index == $qData['correct_index']),
                ]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Quiz mis à jour avec succès !');
    }
}