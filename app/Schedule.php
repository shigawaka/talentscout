<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $table = "schedule";
  public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'user_id',
  	'fullday_event',
  	'title',
  	'start_date',
  	'end_date'
	];
}
