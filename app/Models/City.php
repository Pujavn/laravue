<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'state_id'];

    /**
     * Relationship: City belongs to a State.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Many-to-Many relationship: City can have multiple Users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_city');
    }
}
