<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionFederal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function config()
    {
        return $this->belongsTo(Config::class);
    }

}
