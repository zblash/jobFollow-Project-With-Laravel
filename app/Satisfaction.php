<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satisfaction extends Model
{
    protected $fillable = [
    	'customer_id','appointment_id','status','is_controlled','control_counter','appointment_date','bulkid'
    ];

    public function customer(){
    	return $this->belongsTo('App\Customer','customer_id');
    }
    public function appointment(){
    	return $this->belongsTo('App\Appointment','appointment_id');
    }
}
