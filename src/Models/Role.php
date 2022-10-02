<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class Role extends Model {

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

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function getBySlug() {

        $result = [];
        
        foreach(self::all() as $role)
        {
            $result[$role->slug] = $role;
        }

        return $result;
    }

}