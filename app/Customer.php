<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'address','address_direction','address_coordinates','tc_no','billing','customer_level'
    ];

    
    
    public function appointments(){
    	return $this->hasMany('App\Appointment');
    }
    public function satisfactions(){
        return $this->hasMany('App\Satisfaction');
    }
   protected static function boot() {
         parent::boot();

        static::deleting(function($customer) { 
           $appointments = $customer->appointments->all();
        foreach ($appointments as $appointment) {
            $appointment->employees()->detach();
            $appointment->services()->detach();
            $appointment->satisfactions()->delete();
        }
        $customer->appointments()->delete();
        $customer->satisfactions()->delete();
        });
    }
}
