<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Encyclopedia extends Model
{
    use HasFactory, UUID;
    protected $fillable = ['user_id', 'trash_id'];

    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Trash() {
        return $this->belongsTo(Trash::class, 'trash_id');
    }
}
