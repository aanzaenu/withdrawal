<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    public function banks()
    {
        return $this->belongsToMany('App\Bank');
    }
    public function op()
    {
        return $this->belongsTo('App\User', 'operator');
    }
}
