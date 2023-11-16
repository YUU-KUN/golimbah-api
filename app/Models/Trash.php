<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Storage;
use Str;

class Trash extends Model
{
    use HasFactory, UUID;

    protected $fillable = ['name', 'description', 'category', 'photo', 'game_mode'];

    protected $appends = ['photo_url', 'label'];

    public function getPhotoUrlAttribute() {
        if ($this->photo) {
            return url('/trashes') .'/' . $this->photo;
            // return url('/') . Storage::url('trashes/' . $this->photo);
        }
    }

    function getLabelAttribute() {
        return Str::slug($this->name);
    }
}
