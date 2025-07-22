<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noun extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'accessory_index',
        'accessory_name',
        'area',
        'background_index',
        'background_name',
        'block_number',
        'body_index',
        'body_name',
        'color_histogram',
        'glasses_index',
        'glasses_name',
        'head_index',
        'head_name',
        'index',
        'minted_at',
        'owner_address',
        'png_path',
        'settled_by_address',
        'svg_path',
        'token_id',
        'token_id_last_synced_at',
        'token_uri',
        'weight',
    ];

    protected $casts = [
        'minted_at' => 'datetime',
        'token_id_last_synced_at' => 'datetime',
        'color_histogram' => 'array'
    ];
}
