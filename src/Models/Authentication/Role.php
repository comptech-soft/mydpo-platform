<?php

namespace MyDpo\Models\Authentication;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

// use MyDpo\Performers\Role\SaveRolePermissions;

class Role extends Model {

    use Itemable, Actionable;

    protected $table = 'roles';

    protected $casts = [
        'id' => 'integer',
        'permissions' => 'json',
        'editable' => 'integer',
        'deletable' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'permissions',
        'type',
        'color',
        'editable',
        'deleteabe',
        'created_by',
        'updated_by'
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(
            \App\Models\User::class, 
            'role_users', 
            'role_id', 
            'user_id'
        )->withPivot('customer_id')->withTimestamps();
    }

    // public static function saveRolePermissions($input) {
    //     return (new SaveRolePermissions($input))->Perform();
    // }
    
    /**
     * Se foloseste in config
     */
    public static function getBySlug() {
        $result = [];
        foreach(self::all() as $role)
        {
            $result[$role->slug] = $role;
        }
        return $result;
    }

    public static function SaveDashboardSettings($role_id, $items) {

        $role = self::find($role_id);

        $permissions = !! $role->permissions ? [...$role->permissions] : [];

        $role->permissions = [
            ...$permissions,
            'dashboard' => $items,
        ];

        $role->save();

        return $role->permissions;
    }


}