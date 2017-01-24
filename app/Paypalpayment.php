<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paypalpayment extends Model
{
    public $table = "payment";
       protected $fillable=[
  	
	'id',
  	'payment_id',
  	'user_id',
  	'state'
	];
}
