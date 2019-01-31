<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model{
    // A profile belongs to a user
    public function user(){
    	return $this->belongsTo('App\User');
    }

    // A friend belongs to a user
    public function befriended(){
    	return $this->belongsTo('App\User', 'user_id');
    }
}