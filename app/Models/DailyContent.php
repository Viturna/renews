<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'publish_date',
        'video_url',
        'title',
        'description',
        'unlock_quiz_at',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'unlock_quiz_at' => 'datetime',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
}