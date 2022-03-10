<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'log_sheet_id',
        'invoice_no',
        'date',
        'client_id',
        'gross_weight',
        'transporter_id',
        'vehicle_id',
        'destination',
        'no_of_packs',
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
}
