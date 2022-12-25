<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRemark extends Model
{
    protected $fillable = [
        'remark',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
