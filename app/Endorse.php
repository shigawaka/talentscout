<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endorse extends Model
{
    public $table = "endorse";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'endorsed_id',
  	'endorser_id'
	];
}
