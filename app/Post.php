<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $table = "post";
       protected $fillable=[
  	
	'id',
  	'scout_id',
  	'title',
  	'description',
	'image',
  	'tags',
  	'budget',
  	'rate',
  	'date_posted',
    'status'
	];
  public function getDates()
  {
    return ['created_at', 'updated_at', 'date_posted'];
  }
  public function rating()
    {
        return $this->hasManyThrough('App\Rating','post_id');
    }
  
}
