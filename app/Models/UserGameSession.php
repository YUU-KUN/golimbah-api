<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class UserGameSession extends Model
{
    use HasFactory, UUID;
    protected $fillable = ['game_session_id', 'user_id'];

    public function GameSession() {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
