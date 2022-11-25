<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChilderLand extends Model
{
    use HasFactory;

    protected $table = 'childer_lands';

    protected $fillable = [
        'parent_land_id',
        'rental_retribution',
        'utilization_engagement_type',
        'utilization_engagement_name',
        'allotment_of_use',
        'coordinate',
        'large',
        'present_condition',
        'validity_period_of',
        'validity_period_until',
        'engagement_number',
        'engagement_date',
        'description',
        'application_letter',
        'agreement_letter',
    ];

    public function parentland() { 
        return $this->belongsTo(ParentLand::class);
    }

}
