<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_number',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function locations(){
        return $this->morphToMany(Location::class, 'locationable');
    }
}
