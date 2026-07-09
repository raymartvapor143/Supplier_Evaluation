<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'abbreviation',
        'name',
        'head',
        'designation',
        'responsibility_number',
    ];

    /**
     * Users under this office
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
public function authorizes()
{
    return $this->hasMany(Authorize::class);
}
}
