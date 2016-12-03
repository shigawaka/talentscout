<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'roleID', 'firstname', 'lastname', 'birthday', 'contactno', 'address', 'emailaddress','groupname','profile_image','username','password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * A user belongs to only one course
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group')->withPivot('approved')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function newNotification()
    {
        $notification = new Notification;
        $notification->user()->associate($this);

        return $notification;
    }

    public function friends()
    {
        return $this->belongsToMany('App\User', 'friend_user', 'from_id', 'to_id')
                    ->withPivot('approved');
    }

    // akoy nag Add
    function friendsOfMine()
    {
        return $this->belongsToMany('App\User', 'friend_user', 'from_id', 'to_id')
                    ->withPivot('approved');
    }

    // silay nag add nako
    function friendOf()
    {
        return $this->belongsToMany('App\User', 'friend_user', 'to_id', 'from_id')
                    ->withPivot('approved');
    }

    public function addFriend($user)
    {
        $this->friends()->attach($user->id);
    }

    public function removeFriend($user)
    {
        $this->friends()->detach($user->id);
    }

}
