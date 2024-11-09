<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapUserApp extends Model
{
    use HasFactory;

    protected $table = 'map_users_apps';

    protected $fillable = [
        'user_id',
        'app_id',
        'data_status',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan App
    public function app()
    {
        return $this->belongsTo(App::class, 'app_id');
    }
}
