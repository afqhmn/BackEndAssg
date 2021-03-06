<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name','position', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const RULES=[
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255',
        'password' => 'required|string',
        'position' => 'required|string',
//        'file'     => 'required',
//        'extension'=> 'required|in:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp',
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
