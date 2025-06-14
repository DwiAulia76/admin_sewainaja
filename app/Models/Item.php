<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'description',
        'category',
        'price_per_day',
        'status',
        'photo',
        'video'
    ];

    // Relasi ke user (admin yang menambahkan)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi ke item availability
    public function availabilities()
    {
        return $this->hasMany(ItemAvailability::class);
    }
}