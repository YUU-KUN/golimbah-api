<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Leaderboard extends Model
{
    use HasFactory, UUID;
    protected $fillable = ['game_session_id', 'user_id', 'score'];

    protected $appends=['rank'];

    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GameSession() {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function getRankAttribute() {
        return $this->GameSession->Leaderboards()->where('score', '>=', $this->score)->count();
    }
}
