<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupmember extends Model
{
    protected $table = 'groupmember';
	    public $timestamps = false;
	    protected $fillable=[
  	
	'id',
  	'member'
	];
}
