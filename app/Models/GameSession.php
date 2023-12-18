<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class GameSession extends Model
{
    use HasFactory, UUID;
    protected $fillable = ['time', 'mode', 'status', 'session_code', 'goal_score', 'level'];
    
    public function UserGameSession() {
        return $this->hasMany(UserGameSession::class);
    }

    public function GameSession() {
        return $this->hasMany(GameSession::class);
    }

    public function Leaderboards() {
        return $this->hasMany(Leaderboard::class);
    }
}
