<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
        protected $fillable = [
        'name', 'phone'
    ];
    public function appointments(){
    	return $this->hasMany('App\Appointment');
    }

protected static function boot() {
        parent::boot();

        static::deleting(function($driver) { 
           $appointments = $driver->appointments->all();
        foreach ($appointments as $appointment) {
            $appointment->employees()->detach();
            $appointment->services()->detach();
            $appointment->satisfactions()->delete();
        }
        $driver->appointments()->delete();
            
        });
    }
}