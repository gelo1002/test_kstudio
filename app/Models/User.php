<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Models\Core\Role;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, Notifiable;

    protected $table="t_users";

    /**
    * get the model's input validation rules
    *
    * @param String $action
    * @return Array $rules
    */
    public static function getValidationRules($action)
    {
        $rules = [
            'login' => [
                'email'     => 'required|email',
                'password'  => 'required',
            ],
            'create' => [
                'first_name'    => 'required',
                'last_name'     => 'required',
                'nickname'      => 'required',
                'email'         => 'required|email',
                'password'      => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/'
            ],
            'update' => [
                'first_name'    => 'required',
                'last_name'     => 'required',
                'nickname'      => 'required',
            ],
            'update_password' => [
                'new_password'  => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
            ],
            'delete' => [
                'password'      => 'required',
            ],
        ];
        return $rules[$action];
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'encrypt_id',
        'first_name',
        'last_name',
        'nickname',
        'email',
        'email_hash',
        'password',
        'role_id',
        'active',
        'avatar',
        'status',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role for this user.
     */
    public function role()
    {
        return $this->hasOne('App\Models\Core\Role','id','role_id');
    }

    public function getPermissions(){
        $auth_role = Role::where('id',$this->role_id)->with('permissions')->first();
        $perms = [];
        foreach($auth_role->permissions as $perm){
            array_push($perms,$perm->key);
        }
        return $perms;
    }

    public function hasPermissions($req_keys){
        $user_perms = $this->getPermissions();
        $has_all = true;
        foreach($req_keys as $req_perm){
            if( !in_array($req_perm,$user_perms) ){
                $has_all = false;
            }
        }
        return $has_all;
    }

    /**
     * Get the social network for this user.
     */
    public function userSocialNetwork()
    {
        return $this->hasMany('App\Models\Core\UserSocialNetwork');
    }

    /**
     * Get the files for this user.
     */
    public function userFile()
    {
        return $this->hasMany('App\Models\Core\UserFile');
    }
}
