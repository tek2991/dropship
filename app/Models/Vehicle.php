<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    public function drivers(){
        return $this->belongsToMany(Driver::class, 'driver_vehicle', 'vehicle_id', 'driver_id');
    }

    public function transporters(){
        return $this->belongsToMany(Transporter::class, 'transporter_vehicle', 'vehicle_id', 'transporter_id');
    }
}
