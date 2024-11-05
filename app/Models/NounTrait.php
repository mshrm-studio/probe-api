<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NounTrait extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'layer',
        'png_path',
        'seed_id',
        'svg_path'
    ];
}
