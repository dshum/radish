<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class User extends Authenticatable implements ElementInterface
{
    use ElementTrait; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
