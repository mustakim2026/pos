<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this import

class Unit extends Model
{
    protected $fillable = ['report_date', 'unit_name'];

   public function lines(): HasMany {
        return $this->hasMany(Line::class, 'unit_id');
    }
}
