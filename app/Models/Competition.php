<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $table = 'competitions';
    public $appends = ['race_time', 'position', 'position_by_age'];
    protected $hidden = array('created_at', 'updated_at');

    public function runners()
    {
        return $this->belongsTo('App\Models\Runner', 'runners_id');
    }

    public function races()
    {
        return $this->belongsTo('App\Models\Race', 'races_id');
    }

    public function getRaceTimeAttribute()
    {
        $initial_date = new DateTime($this->race_start_time);
        $end_date = new DateTime($this->race_end_time);

        if ($end_date < $initial_date) {
            $custom_end_date = $end_date->modify('+1 day');
        } else {
            $custom_end_date = $end_date;
        }

        return $initial_date->diff($custom_end_date)->format('%H:%I:%S');
    }

    public function getPositionAttribute()
    {
        $race = Race::find($this->races_id);
        $competition = $race->competitions()->get();

        $competitionsOrderly = $competition->sortBy('race_time');

        $position = $competitionsOrderly->pluck('id')->search($this->id);

        return $position + 1;
    }

    public function getPositionByAgeAttribute()
    {
        $race = Race::find($this->races_id);
        $runner = Runner::find($this->runners_id);
        $competition = $race->competitions()->with('runners')->get();

        if ($runner->age >= 15 && $runner->age < 25) {
            $filterAge = $competition->whereBetween('runners.age', [18, 25]);
        } elseif ($runner->age >= 25 && $runner->age < 35) {
            $filterAge = $competition->whereBetween('runners.age', [25, 35]);
        } elseif ($runner->age >= 35 && $runner->age < 45) {
            $filterAge = $competition->whereBetween('runners.age', [35, 45]);
        } elseif ($runner->age >= 45 && $runner->age < 55) {
            $filterAge = $competition->whereBetween('runners.age', [45, 55]);
        } elseif ($runner->age >= 55) {
            $filterAge = $competition->whereBetween('runners.age', [55, 99]);
        }

        $competitionsOrderly = $filterAge->sortBy('race_time');

        $position = $competitionsOrderly->pluck('id')->search($this->id);

        return $position + 1;
    }
}
