<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFile extends Model
{
    use SoftDeletes;

    protected $table = 't_users_files';

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
                'file'          => 'required',
                'description'   => 'required',
            ],
            'file'=> [
                'file_path'     => 'required',
            ],
            'delete' => [
                'eid'           => 'required',
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
        'user_id',
        'file_path',
        'latitude',
        'longitude',
        'description',
        'status'
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
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
}
