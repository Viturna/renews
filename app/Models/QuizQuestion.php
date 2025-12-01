<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['daily_content_id', 'question_text', 'points_value'];

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}