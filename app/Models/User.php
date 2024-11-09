<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_code',
        'user_fullname',
        'department',
        'user_password',
        'data_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'user_password' => 'hashed',
    ];

    // Relasi dengan tabel map_users_apps
    public function apps()
    {
        return $this->belongsToMany(
            App::class,
            'map_users_apps', // Nama tabel pivot
            'user_id', // Foreign key di tabel pivot
            'app_id'   // Foreign key di tabel apps
        )->wherePivot('data_status', true); // Filter untuk hanya data aktif
    }
}
