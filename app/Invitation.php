<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public $table = "invitation";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'post_id',
  	'talent_id',
  	'status'
	];
}
