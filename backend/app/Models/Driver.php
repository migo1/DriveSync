<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user that owns the driver.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trips for the driver.
     */

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
