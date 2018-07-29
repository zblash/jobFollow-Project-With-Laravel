<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
            protected $fillable = [
        'name'
    ];

     public function appointments(){
    	return $this->belongsToMany('App\Service','service_appointment');
    }
    
   
}
