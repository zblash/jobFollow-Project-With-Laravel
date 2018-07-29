<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
        protected $fillable = [
        'name', 'phone', 'address','tc_no','tc_pic','bank','bank_account_owner','iban','confirmed'
    ];

    public function Appointments(){
    	return $this->belongsToMany('App\Appointment','employee_appointment');
    }
    public function Customers(){
    	return $this->belongsToMany('App\Customer','employee_customer');
    }
    public function profits(){
    	return $this->hasMany('App\Profit');
    }
    protected static function boot() {
         parent::boot();

        static::deleting(function($employee) {   
        $employee->Appointments()->detach();
        $employee->profits()->delete();
        });
    }
}
