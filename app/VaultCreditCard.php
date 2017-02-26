<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VaultCreditCard extends Model
{
    public $table = "vault";
    public $timestamps = false;
     protected $fillable=[
	'creditcardID',
  	'user_id'
	];
}
