<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'file_name',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
