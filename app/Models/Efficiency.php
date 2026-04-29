<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\DailyProduction;

class Efficiency extends Model
{
     protected $fillable = ['report_date', 'unit_name', 'observer'];

   public function dailyProductions(): HasMany 
    {
        return $this->hasMany(DailyProduction::class, 'efficiency_id');
        
    }

}
