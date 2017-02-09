<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $table = "rating";
    public $timestamps = false;
       protected $fillable=[
  	
	'id',
  	'user_id',
  	'score',
  	'demerit'
	];
	 public function post()
    {
        return $this->belongsTo('App\Post','id');
    }
}
