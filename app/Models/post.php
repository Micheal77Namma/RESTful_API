<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'descpription',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\user');
    }
}
