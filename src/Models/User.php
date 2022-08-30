<?php

namespace MyDpo\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use MyDpo\Models\Role;
use MyDpo\Models\UserSetting;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'k_id',
        'last_name',
        'first_name',
        'email',
        'password',
        'avatar',
        'phone',
        'permissions',
        'last_login',
        'editable',
        'deletable',
        'created_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'json',
        'avatar' => 'json',
        'id' => 'integer',
        'editable' => 'integer',
        'deletable' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $with = [
        'roles',
    ];

    protected $appends  = [
        'full_name', 
        'icon', 
        'initials',
        'active',
        'role',
        'my_settings'
    ];

    /**
     * 
     * ATTRIBUTES
     * 
     */
    public function getActiveAttribute() {
        return !! $this->email_verified_at;
    }

    public function getRoleAttribute() {
        $r = NULL;
        foreach($this->roles as $i => $role)
        {
            if($role->type == config('app.platform') )
            {
                $r = $role;
            }
        }
        return $r;
    }

    public function getFullNameAttribute() {
        return collect([
			$this->last_name,
            $this->first_name,
		])->filter( function($value) {
			return strlen(trim($value)) != 0;
		})->implode(' ');
    }
    
    public function getIconAttribute() {
        if(! $this->avatar )
        {
            return NULL;
        }
        return $this->avatar['url'];
    }

    public function getInitialsAttribute() {
        $r = collect([
			$this->last_name,
            $this->first_name,
		])->filter( function($value) {
			return strlen(trim($value)) != 0;
		})->map( function($value) { return $value[0]; })->implode('');

        return strtoupper($r);
    }

    public function getMySettingsAttribute() {
        return $this->settings()->get()->pluck('value', 'code');
    }

    /**
     * 
     * RELATIONS
     * 
     */

    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            Role::class, 
            'role_users', 
            'user_id', 
            'role_id'
        )->withTimestamps();
    }

    function settings() {
        return $this->hasMany(UserSetting::class, 'user_id');
    }

    public function inRoles($slugs) {

        $r = FALSE;

        foreach($this->roles as $i => $role)
        {
            $r = FALSE;
            foreach($slugs as $j => $slug)
            {
                if($role->slug == $slug)
                {
                    $r = TRUE;
                }
            }
            return $r;
        }

    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }
}
