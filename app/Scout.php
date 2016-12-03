<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
  public $table = "scout";
  public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'score'
	];
}
