<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'apps';

    protected $fillable = [
        'app_code',
        'app_name',
        'app_group',
        'app_url',
        'data_status',
    ];

    // Relasi dengan tabel map_users_apps
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'map_users_apps', // Nama tabel pivot
            'app_id',  // Foreign key di tabel pivot
            'user_id'  // Foreign key di tabel users
        )->wherePivot('data_status', true); // Filter untuk hanya data aktif
    }
}
