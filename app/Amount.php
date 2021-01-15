<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    protected $fillable = ['bank_id', 'nominal', 'status', 'user_id'];
    public function banks()
    {
        return $this->belongsTo('App\Bank', 'bank_id');
    }
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
