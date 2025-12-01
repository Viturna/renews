<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyContent;
use Carbon\Carbon;

class QuizController extends Controller
{
    /**
     * Affiche le quiz du contenu de la veille.
     */
    public function show()
    {
        // On cherche le contenu publié HIER
        $yesterdayContent = DailyContent::with(['theme', 'questions.answers'])
            ->whereDate('publish_date', Carbon::yesterday())
            ->first();

        // Si pas de contenu ou pas de questions
        if (!$yesterdayContent || $yesterdayContent->questions->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Pas de quiz disponible pour la veille !');
        }

        return view('quiz', ['content' => $yesterdayContent]);
    }
}