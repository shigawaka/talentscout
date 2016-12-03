<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    public $table = "proposal";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'user_id',
  	'post_id',
  	'body',
  	'proposed_rate'
	];
}
