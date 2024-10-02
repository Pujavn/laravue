<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'gender',
        'status',
        'activation_token',
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
    ];

    // Relationship for permanent states with type filter
    public function permanentStates()
    {
        return $this->belongsToMany(State::class, 'user_state')
            ->wherePivot('type', 'permanent');
    }

    // Relationship for temporary states with type filter
    public function temporaryStates()
    {
        return $this->belongsToMany(State::class, 'user_state')
            ->wherePivot('type', 'temporary');
    }

    // Relationship for permanent cities with type filter
    public function permanentCities()
    {
        return $this->belongsToMany(City::class, 'user_city')
            ->wherePivot('type', 'permanent');
    }

    // Relationship for temporary cities with type filter
    public function temporaryCities()
    {
        return $this->belongsToMany(City::class, 'user_city')
            ->wherePivot('type', 'temporary');
    }
}
