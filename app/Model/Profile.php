<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model{
    // A profile belongs to a user
    public function user(){
    	return $this->belongsTo('App\User');
    }
}