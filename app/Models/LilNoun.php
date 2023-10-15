<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LilNoun extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'index',
        'token_id',
        'token_uri',
        'background_index',
        'body_index',
        'accessory_index',
        'head_index',
        'glasses_index',
        'background_name',
        'body_name',
        'accessory_name',
        'head_name',
        'glasses_name'
    ];
}
