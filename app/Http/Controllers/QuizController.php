<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyContent;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizController extends Controller
{

    public function show()
    {
        $yesterdayContent = DailyContent::with(['theme', 'questions.answers'])
            ->whereDate('publish_date', Carbon::yesterday())
            ->first();

        if (!$yesterdayContent || $yesterdayContent->questions->isEmpty()) {
            if (!$yesterdayContent) {
                return redirect()->route('dashboard')->with('error', 'Pas de quiz disponible pour la veille !');
            }
        }

        return view('quiz', ['content' => $yesterdayContent]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'exists:quiz_answers,id',
        ]);

        $user = Auth::user();
        $xpGained = 0;
        $correctCount = 0;

        foreach ($request->answers as $questionId => $answerId) {
            $answer = QuizAnswer::find($answerId);

            if ($answer && $answer->is_correct) {
                $xpGained += 25;
                $correctCount++;
            }
        }

        if ($xpGained > 0) {
            $user->addXp($xpGained);
        }

        return redirect()->route('dashboard')->with('success', 
            "Quiz terminé ! Tu as eu $correctCount bonnes réponses et gagné $xpGained XP !");
    }
}