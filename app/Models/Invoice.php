<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'log_sheet_id',
        'invoice_no',
        'date',
        'client_id',
        'gross_weight',
        'no_of_packs',
        'delivery_status',
        'delivery_state_id',
        'updated_by',
        'remarks',
        'transporter_id',
        'vehicle_id',
        'destination',
        'driver_id',
    ];

    public function logSheet()
    {
        return $this->belongsTo(LogSheet::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientUser(){
        return $this->hasOneThrough(User::class, Client::class, 'id', 'id', 'client_id', 'user_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function hasImages()
    {
        return $this->images()->count() > 0;
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deliveryState()
    {
        return $this->belongsTo(DeliveryState::class);
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

    public function locations(){
        return $this->morphToMany(Location::class, 'locationable');
    }
}
