<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionState extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = ['percentage', 'last_update_date', 'last_update_hour'];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }
}
