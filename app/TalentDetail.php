<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TalentDetail extends Model
{
    public $table = "talent_details";
    public $timestamps = false;
     protected $fillable=[
  	
	'talent_id',
  	'category',
  	'talent'
	];

	public function userTalentDetail(){
		return $this->belongsTo('App\User', 'id');
	}
}
