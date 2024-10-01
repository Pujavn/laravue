<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: State has many Cities.
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Many-to-Many relationship: State can have multiple Users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_state');
    }
}
