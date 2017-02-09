<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $table = "subscription";
  public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'price',
  	'descriptions'
	];
}
