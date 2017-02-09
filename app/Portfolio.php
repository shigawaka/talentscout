<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    public $table = "portfolio";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'user_id',
  	'event_name',
  	'event_date',
  	'file',
  	'post_id',
  	'description'
	];
}
