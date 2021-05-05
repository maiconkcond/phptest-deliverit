<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfRace extends Model
{
    use HasFactory;

    protected $table = 'type_of_races';
    protected $hidden = array('created_at', 'updated_at');

    public function races()
    {
        return $this->hasMany('App\Models\Race', 'type_of_races_id');
    }
}
