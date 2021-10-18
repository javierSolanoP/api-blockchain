<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'data',
        'previous_block',
        'created'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
