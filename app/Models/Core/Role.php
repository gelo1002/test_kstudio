<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table="t_roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'encrypt_id',
      'name',
      'key',
      'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the permissions for this role.
     */
    public function permissions(){
        return $this->belongsToMany('App\Models\Core\Permission','t_roles_permissions','role_id','permission_id');
    }

}
