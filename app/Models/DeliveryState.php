<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryState extends Model
{
    use HasFactory;

    const STATE_PENDING = '1';
    const STATE_DELIVERED = '2';
    const STATE_CANCELED = '3';

    protected $fillable = [
        'name',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
