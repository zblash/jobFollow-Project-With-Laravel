<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = [
    	'employee_id','profit'
    ]; 

    public function employee(){
    	return $this->belongsTo('App\Employee','employee_id');
    }
}
