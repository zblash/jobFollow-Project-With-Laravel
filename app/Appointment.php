<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id', 'appointment_type','service_pay','employee_pay','payment_type','driver_id','appointment_time','next_appointment_time','appointment_range','is_employee_profit','is_cancelled'
    ];

    public function employees(){
    	return $this->belongsToMany('App\Employee','employee_appointment');
    }

    public function services(){
        return $this->belongsToMany('App\Service','service_appointment');
    }

    public function satisfactions(){
        return $this->hasMany('App\Satisfaction');
    }

    public function driver(){
    	return $this->belongsTo('App\Driver','driver_id');
    }
    
    public function customer(){
    	return $this->belongsTo('App\Customer','customer_id');
    }
    protected static function boot() {
         parent::boot();

        static::deleting(function($appointment) { 
            $appointment->services()->detach();
            $appointment->employees()->detach();
            $appointment->satisfactions()->delete();
        });
    }
}
