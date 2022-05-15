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
        'updated_by',
        'remarks',
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
}
