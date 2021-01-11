<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public function withdrawals()
    {
        return $this->belongsToMany('App\Withdrawal');
    }
}
