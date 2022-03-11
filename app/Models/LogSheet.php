<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSheet extends Model
{
    protected $fillable = [
        'log_sheet_no',
        'date',
        'transporter_id',
        'vehicle_id',
        'destination',
        'driver_id',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function driverUser(){
        return $this->hasOneThrough(User::class, Driver::class, 'id', 'id', 'driver_id', 'user_id');
    }

    public function transporterUser(){
        return $this->hasOneThrough(User::class, Transporter::class, 'id', 'id', 'transporter_id', 'user_id');
    }
}
