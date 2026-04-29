<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Efficiency;

class DailyProduction extends Model
{
  protected $fillable = [
    'efficiency_id', 
    'line_no', 
    'po_no', 
    'style_model_no', 
    'item', 
    'target', 
    'output', 
    'remarks'
];

    
    
public function efficiency(): BelongsTo 
    {
        return $this->belongsTo(Efficiency::class, 'efficiency_id');
    }
}
