<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $table = 'races';
    protected $hidden = array('created_at', 'updated_at');

    public function typeOfRace()
    {
        return $this->belongsTo('App\Models\TypeOfRace', 'type_of_races_id');
    }

    public function competitions()
    {
        return $this->hasMany('App\Models\Competition', 'races_id');
    }
}
