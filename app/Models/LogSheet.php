<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSheet extends Model
{
    protected $fillable = ['log_sheet_no', 'date'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
