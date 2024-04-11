<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    public function rawDataImports()
    {
        return $this->hasMany(RawDataImport::class);
    }

    public function managers()
    {
        return $this->morphedByMany(Manager::class, 'locationable');
    }

    public function clients()
    {
        return $this->morphedByMany(Client::class, 'locationable');
    }

    public function drivers()
    {
        return $this->morphedByMany(Driver::class, 'locationable');
    }

    public function vehicles()
    {
        return $this->morphedByMany(Vehicle::class, 'locationable');
    }

    public function transporters()
    {
        return $this->morphedByMany(Transporter::class, 'locationable');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function logsheets(){
        return $this->hasMany(LogSheet::class);
    }
}
