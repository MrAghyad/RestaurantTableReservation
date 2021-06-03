<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_id',
        'starting_date',
        'ending_date'
    ];

    public function restaurantTable()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id', 'id');
    }
}
