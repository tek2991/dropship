<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DriverTransporter extends Pivot
{
    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function transporter(){
        return $this->belongsTo(Transporter::class);
    }
}
