<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DreamNoun extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_seed_id',
        'background_seed_id',
        'body_seed_id',
        'dreamer',
        'glasses_seed_id',
        'head_seed_id',
    ];

    /**
     * Get the accessory of the dream noun.
     */
    public function accessory(): BelongsTo
    {
        return $this->belongsTo(NounTrait::class, 'accessory_seed_id', 'seed_id')->where('layer', 'accessory');
    }

    /**
     * Get the background of the dream noun.
     */
    public function background(): BelongsTo
    {
        return $this->belongsTo(NounTrait::class, 'background_seed_id', 'seed_id')->where('layer', 'background');
    }

    /**
     * Get the body of the dream noun.
     */
    public function body(): BelongsTo
    {
        return $this->belongsTo(NounTrait::class, 'body_seed_id', 'seed_id')->where('layer', 'body');
    }

    /**
     * Get the glasses of the dream noun.
     */
    public function glasses(): BelongsTo
    {
        return $this->belongsTo(NounTrait::class, 'glasses_seed_id', 'seed_id')->where('layer', 'glasses');
    }

    /**
     * Get the head of the dream noun.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(NounTrait::class, 'head_seed_id', 'seed_id')->where('layer', 'head');
    }
}
