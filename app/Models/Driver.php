<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transporters(){
        return $this->belongsToMany(Transporter::class, 'driver_transporter', 'driver_id', 'transporter_id');
    }

    public function vehicles(){
        return $this->belongsToMany(Vehicle::class, 'driver_vehicle', 'driver_id', 'vehicle_id');
    }

    // public function invoices(){
    //     return $this->hasManyThrough(Invoice::class, LogSheet::class, 'driver_id', 'log_sheet_id');
    // }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
