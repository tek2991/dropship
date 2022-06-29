<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'dob',
        'address',
        'phone',
        'alternate_phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
    ];

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTransporter(): bool
    {
        return $this->hasRole('transporter') && $this->transporter()->exists();
    }

    public function transporter()
    {
        return $this->hasOne(Transporter::class);
    }

    public function isDriver(): bool
    {
        return $this->hasRole('driver') && $this->driver()->exists();
    }

    public function driver(){
        return $this->hasOne(Driver::class);
    }

    public function isClient(): bool
    {
        return $this->hasRole('client') && $this->client()->exists();
    }

    public function client(){
        return $this->hasOne(Client::class);
    }

    public function createdImages()
    {
        return $this->hasMany(Image::class, 'created_by');
    }

    public function manager()
    {
        return $this->hasOne(Manager::class);
    }

    public function locations()
    {
        return $this->hasManyThrough(Location::class, Manager::class);
    }
}
