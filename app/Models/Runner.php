<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Runner extends Model
{
    use HasFactory;

    protected $table = 'runners';
    public $appends = ['age'];
    protected $hidden = array('created_at', 'updated_at');

    public function competitions()
    {
        return $this->hasMany('App\Models\Competition', 'runners_id');
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }
}
