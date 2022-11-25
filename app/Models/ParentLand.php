<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentLand extends Model
{
    use HasFactory;

    protected $table = 'parent_lands';

    protected $fillable = [
        'owner_id',
        'certificate_number',
        'certificate_date',
        'item_name',
        'address',
        'large',
        'asset_value'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'parent_land_admins');
    }

    public function childerland() { 
        return $this->hasMany(ChilderLand::class);
    }

    // public function brands()
    // {
    //     return $this->belongsToMany(Brand::class, 'brand_store');
    // }
}
