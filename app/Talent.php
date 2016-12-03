<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    public $table = "talent";
    public $timestamps = false;
     protected $fillable=[
  	
	'talentid',
  	'talents'
	];
}
