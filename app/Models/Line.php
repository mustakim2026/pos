<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this import

class Line extends Model
{
    protected $fillable = ['unit_id', 'line_no', 'remarks'];

    public function unit(): BelongsTo {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
