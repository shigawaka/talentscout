<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Featured extends Model
{
    public $table = "featured";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'isProfile',
  	'isFeedback',
  	'image',
  	'profile_id',
  	'start_date',
  	'end_date'
	];
}
