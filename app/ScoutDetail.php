<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoutDetail extends Model
{
    public $table = "scout_details";
    public $timestamps = false;
     protected $fillable=[
  	
	'scout_id',
  	'category',
  	'talent'
	];
}
