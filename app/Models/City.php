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
     * Many-to-Many relationship: City can have multiple Users as Permanent.
     */
    public function permanentUsers()
    {
        return $this->belongsToMany(User::class, 'user_city')
                    ->wherePivot('type', 'permanent'); // Filter by 'permanent' type
    }

    /**
     * Many-to-Many relationship: City can have multiple Users as Temporary.
     */
    public function temporaryUsers()
    {
        return $this->belongsToMany(User::class, 'user_city')
                    ->wherePivot('type', 'temporary'); // Filter by 'temporary' type
    }
}
