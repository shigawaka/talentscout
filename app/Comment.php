<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = "comment";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'user_id',
  	'post_id',
  	'body',
  	'date_posted'
	];
}
