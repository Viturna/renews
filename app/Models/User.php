<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'current_level_id',
        'current_xp',
        'currency_balance',
        'current_streak',
        'last_streak_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_streak_at' => 'datetime',
        ];
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }

    public function addXp(int $amount)
    {
        $this->increment('current_xp', $amount);

        $nextLevel = Level::where('level_number', '>', $this->level->level_number)
                          ->orderBy('level_number', 'asc')
                          ->first();

        while ($nextLevel && $this->current_xp >= $nextLevel->xp_threshold) {
            $this->update(['current_level_id' => $nextLevel->id]);
            
            $nextLevel = Level::where('level_number', '>', $nextLevel->level_number)
                              ->orderBy('level_number', 'asc')
                              ->first();
        }
    }
}