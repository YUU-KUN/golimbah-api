<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\UUID;
use Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'role',
        'profession',
        'username',
        'password',
        'photo',
        'email',
        'phone',
        'dob',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute() {
        // $photo_path = 'users/defaults/default.png';
        $photo_path = 'https://thispersondoesnotexist.com';
        // $photo_path = 'https://picsum.photos/seed/' . $this->id . '/200/300';
        if ($this->photo) {;
            $photo_path = url('/') . Storage::url('users/' . $this->photo);
        }
        return $photo_path;
    }
}
