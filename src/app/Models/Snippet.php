<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = [
        'title',
        'tag',
        'code',
        'result',
        'user_id',
        'favorite'
    ];

    protected $casts = [
        'favorite' => 'boolean',
    ];
}
