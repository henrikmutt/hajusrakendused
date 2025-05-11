<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FightCard extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'blue_corner',
        'red_corner',
        'uuid',
    ];

}
