<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	    protected $table = 'group';
	    public $timestamps = false;
        protected $fillable = ['id', 'groupname', 'contactno', 'emailaddressg', 'founded', 'user_name', 'password', 'member','profile_image'];

    protected $hidden = ['password', 'remember_token'];

}
