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
    
    public function imports(){
        return $this->hasMany(Import::class);
    }

    public function rawDataImports(){
        return $this->hasMany(RawDataImport::class);
    }

    public function managers()
    {
        return $this->morphedByMany(Manager::class, 'locationable');
    }
}
