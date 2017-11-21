<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'paymaya_account', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {

        return $this->belongsTo('App\Role');

    }

    public function isAdmin () {

        return $this->role['name'] == config('constants.USER_ROLE_ADMIN');

    }

    public function isOwner () {

        return $this->role['name'] == config('constants.USER_ROLE_OWNER');

    }

    public function isCustomer () {

        return $this->role['name'] == config('constants.USER_ROLE_CUSTOMER');

    }

}
