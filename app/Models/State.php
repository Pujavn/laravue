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
     * Many-to-Many relationship: State can have multiple Users as Permanent.
     */
    public function permanentUsers()
    {
        return $this->belongsToMany(User::class, 'user_state')
                    ->wherePivot('type', 'permanent'); // Filter by 'permanent' type
    }

    /**
     * Many-to-Many relationship: State can have multiple Users as Temporary.
     */
    public function temporaryUsers()
    {
        return $this->belongsToMany(User::class, 'user_state')
                    ->wherePivot('type', 'temporary'); // Filter by 'temporary' type
    }
}
