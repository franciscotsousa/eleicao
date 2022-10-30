<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function cities() : HasMany
    {
        return $this->hasMany(City::class);
    }

    public function candidates() : HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function electionFederals() : HasMany
    {
        return $this->hasMany(ElectionFederal::class);
    }

    public function electionStates() : HasMany
    {
        return $this->hasMany(ElectionState::class);
    }
}
