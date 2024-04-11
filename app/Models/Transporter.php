<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_active',
        'is_first_party',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function drivers(){
        return $this->belongsToMany(Driver::class, 'driver_transporter', 'transporter_id', 'driver_id');
    }

    public function vehicles(){
        return $this->belongsToMany(Vehicle::class, 'transporter_vehicle', 'transporter_id', 'vehicle_id');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function locations(){
        return $this->morphToMany(Location::class, 'locationable');
    }
}
