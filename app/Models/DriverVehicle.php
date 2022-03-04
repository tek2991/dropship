<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DriverVehicle extends Pivot
{
    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
