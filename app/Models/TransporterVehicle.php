<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TransporterVehicle extends Pivot
{
    public function transporter(){
        return $this->belongsTo(Transporter::class);
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
