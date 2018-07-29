<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
    	'title','bulkid','campaign_date'
    ];
}
