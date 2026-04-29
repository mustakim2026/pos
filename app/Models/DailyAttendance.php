<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
protected $fillable = [
    'efficiency_id', 'line_no', 'po_no', 'style_model_no', 'item', 'allocated_Hrs',
    'OP1', 'HP1', 'OP2', 'HP2', 'OP3', 'HP3', 'OP4', 'HP4', 
    'OP5', 'HP5', 'OP6', 'HP6', 'OP7', 'HP7', 'OP8', 'HP8','w_hrs'
];

    public function efficiency(): BelongsTo 
    {
        return $this->belongsTo(Efficiency::class, 'efficiency_id');
    }

}
