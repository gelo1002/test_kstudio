<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSocialNetwork extends Model
{
    use SoftDeletes;

    protected $table = 't_users_social_network';

    /**
    * get the model's input validation rules
    *
    * @param String $action
    * @return Array $rules
    */
    public static function getValidationRules($action)
    {

        $rules = [
            'create' => [
                'user_id'                  => 'required',
                'user_social_network_id'    => 'required',
                'social_network_id'         => 'required',
                'social_network_avatar'     => 'required',
            ],
            'update' => [
                'user_id'                  => 'required',
                'user_social_network_id'    => 'required',
                'social_network_id'         => 'required',
                'social_network_avatar'     => 'required',
            ]
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
        'user_id',
        'user_social_network_id',
        'social_network_id',
        'social_network_avatar'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the user for this user social network.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
