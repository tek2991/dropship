<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawDataImport extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_sheet',
        'date',
        'invoice_no',
        'invoice_date',
        'payer',
        'payer_name',
        'gross_weight',
        'transporter_name',
        'container_id',
        'destination',
        'no_of_packs',
        'driver_no',
        'is_processed',
        'location_id',
    ];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
