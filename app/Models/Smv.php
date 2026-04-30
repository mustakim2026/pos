<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smv extends Model
{
    protected $fillable = [
        'po_no', 'style_model_no', 'item', 
        'smv', 'CM_Dz', 'CM_pcs', 'fob_pcs', 'remarks'
    ];

    // This ensures these fields are treated as numbers, not strings
    protected $casts = [
        'smv' => 'decimal:2',
        'CM_Dz' => 'decimal:2',
        'CM_pcs' => 'decimal:2',
        'fob_pcs' => 'decimal:2',
    ];
}
