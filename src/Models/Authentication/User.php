<?php

namespace MyDpo\Models\Authentication;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Models\Authentication\Role;
// use MyDpo\Models\RoleUser;
// use MyDpo\Models\Customer\Customer;
// use MyDpo\Models\UserCustomer;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
// use MyDpo\Performers\UserSetting\SaveSetting;
// use MyDpo\Performers\User\UpdatePermissions;
// use MyDpo\Performers\User\UpdateStatus;
use MyDpo\Rules\User\Oldpassword;
// use MyDpo\Scopes\NotdeletedScope;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail {
    use HasApiTokens, HasFactory, Notifiable, Itemable, Actionable;

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
        'email_verified_at',
        'activated_at',
        'permissions',
        'last_login',
        'user_customers_count',
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
        'activated_at' => 'datetime',
        'permissions' => 'json',
        'avatar' => 'json',
        'id' => 'integer',
        'editable' => 'integer',
        'deletable' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'user_customers_count' => 'integer',
    ];

    protected $with = [
        'roles',
        // 'customers',
    ];

    protected $appends  = [
        'full_name', 
        'icon', 
        'initials',
        'active',
        'role',
        'my_settings'
    ];

    // protected static function booted() {
    //     static::addGlobalScope( new NotdeletedScope() );
    // }

    /** *************************
     * ATTRIBUTES               *
     ****************************/
    
    public function getActiveAttribute() {
        return !! $this->email_verified_at && !! $this->activated_at;
    }

    /**
     * Role By Platform = Rolul potrivit platformei 
     **/
    public function getRoleAttribute() {
        
        if(config('app.platform') == 'admin')
        {
            $r = NULL;
            foreach($this->roles as $i => $role)
            {
                if($role->type == config('app.platform'))
                {
                    $r = $role;
                }
            }
            return $r;
        }

        if(config('app.platform') == 'b2b')
        {
            if( !! ($customer_id = request()->customer_id))
            {
                $role_user = RoleUser::where('customer_id', $customer_id)->where('user_id', $this->id)->first();

                if($role_user)
                {
                    return $role_user->role;
                }
            }
        }

        return NULL;
    }

    public function getFullNameAttribute() {
        return collect([
			$this->last_name,
            $this->first_name,
		])->filter( function($value) {
			return $value && (strlen(trim($value)) != 0);
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

    /** *************************
     * RELATIONS                *
     ****************************/
    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            Role::class, 
            'role_users', 
            'user_id', 
            'role_id'
        )
        ->withPivot('customer_id')
        ->withTimestamps();
    }

    // public function customers() {
    //     /**
    //      * la ce customeri sunt eu printre cei care au conturi: customers-persons
    //      */
    //     if( config('app.platform') == 'admin' )
    //     {
    //         /**
    //          * Daca sunt pe platforma admin nu se pune problema sa fiu user al vreunui customer
    //          */
    //         return $this->belongsToMany(
    //             Customer::class, 
    //             'customers-persons', 
    //             'user_id', 
    //             'customer_id'
    //         )->whereRaw('1 = 0');
    //     }

    //     /**
    //      * Daca sunt pe platforma b2b ==> ce gasesc in [customers-persons]
    //      */
    //     return $this->belongsToMany(
    //         Customer::class, 
    //         'customers-persons', 
    //         'user_id', 
    //         'customer_id'
    //     )
    //     ->withPivot('phone', 'newsletter', 'locale', 'department_id', 'created_by', 'updated_by')
    //     ->withTimestamps();
    // }

    function settings() {
        return $this->hasMany(UserSetting::class, 'user_id')->where('platform', config('app.platform'));
    }

    // function usercustomers() {
    //     return $this->hasMany(UserCustomer::class, 'user_id');
    // }

    /** *************************
     * ROLURI.PERMISIUNI        *
     ****************************/

    

    /** *************************
     * ITEMS                    *
     ****************************/

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query(), __CLASS__))->Perform();
    // }

    /** *************************
     * ACTIONS                  *
     ****************************/
    // public static function doAction($action, $input) {

    //     /**
    //      * creare cont - parola #49
    //      * sunt pe platforma de client - rol de master - dau adaugare new user. aici trebuie scos campul de parola
    //      */
    //     if(config('app.platform') == 'b2b')
    //     {
    //         if( ! $input['password'] )
    //         {
    //             $input['password'] = $input['password_confirmation'] = \Str::random(10) . 'aA?1';
    //         }
    //     }

    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doUpdate($input, $user) {

    //     if( array_key_exists('avatar', $input) && $input['avatar'] && ($input['avatar'] instanceof UploadedFile))
    //     {
    //         $input['avatar'] = self::saveFile($input['avatar']);
    //     }

    //     if( array_key_exists('active', $input) )
    //     {   
    //         if( ($input['active'] == 'false') || ! $input['active'])
    //         {
    //             $input['email_verified_at'] = NULL;
    //         }
    //         else
    //         {
    //             $input['email_verified_at'] = \Carbon\Carbon::now();
    //         }
    //     }

    //     if(array_key_exists('role_id', $input) && $input['role_id'])
    //     {

    //         $role = RoleUser::where('user_id', $user->id)->whereNull('customer_id')->first();

    //         if(!$role)
    //         {
    //                 RoleUser::create([
    //                     'user_id' => $user->id,
    //                     'role_id' => $input['role_id'],
    //                     'customer_id' => NULL,
    //                 ]);
    //             }
    //         else
    //         {
    //             $role->role_id = $input['role_id'];
    //             $role->save();
    //         }
    //     }

    //     $user->refresh();

    //     $user->update($input);

    //     return $user;
    // }

    // public static function saveFile($file) {
    //     $ext = strtolower($file->extension());

    //     if(in_array($ext, ['png', 'jpg', 'jpeg']))
    //     {
    //         $filename = md5(time()) . '-' . \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
    //         $result = $file->storeAs('users-avatars/' .  \Auth::user()->id, $filename, 's3');

    //         $inputdata = [
    //             'file_original_name' => $file->getClientOriginalName(),
    //             'file_original_extension' => $file->extension(),
    //             'file_full_name' => $filename,
    //             'file_mime_type' => $file->getMimeType(),
    //             'file_upload_ip' => request()->ip(),
    //             'file_size' => $file->getSize(),
    //             'url' => config('filesystems.disks.s3.url') . $result,
    //             'created_by' => \Auth::user()->id,
    //         ];
            
    //         return $inputdata;
    //     }
    //     else
    //     {
    //         throw new \Exception('Fișier incorect.');
    //     }
    // }

    // public static function doInsert($input, $user) {
    //     $user = self::create([
    //         'last_name' => $input['last_name'],
    //         'first_name' => $input['first_name'],
    //         'email' => $input['email'],
    //         'type' => $input['type'],
    //         'phone' => $input['phone'],
    //         'avatar' => array_key_exists('avatar', $input) ? $input['avatar'] : NULL,
    //         'password' => Hash::make($input['password']),
    //         'email_verified_at' => ($input['type'] == 'b2b' ? NULL : $now = \Carbon\Carbon::now()),
    //         'activated_at' => ($input['type'] == 'b2b' ? NULL : $now),
    //     ]);

    //     if(array_key_exists('role_id', $input) && $input['role_id'])
    //     {
    //         RoleUser::create([
    //             'user_id' => $user->id,
    //             'role_id' => $input['role_id'],
    //             'customer_id' => NULL,
    //         ]);
    //     }

    //     $user->refresh();

    //     return $user;
    // }

    // public static function doDelete($input, $user) {
    //     $user->deleted = 1;
    //     $user->email = $user->id . '#' . $user->email;
        
    //     $user->save();

    //     $user->refresh();

    //     return $user;
    // }

    public static function GetRules($action, $input) {

        if($action == 'changepassword')
        {
            return [
                'oldpassword' => [
                    'required',
                    new Oldpassword($input),
                ],

                'password' => [
                    'required', 
                    'confirmed', 
                    Rules\Password::defaults()->mixedCase()->letters()->numbers()->symbols()
                ]
            ];
        }
        
    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'email' => [
    //             'required', 
    //             'string', 
    //             'email', 
    //             'max:255', 
    //             'unique:users,email'
    //         ],
    //     ];

    //     if($action == 'insert')
    //     {
    //         $result['type'] = [
    //             'required', 
    //             'in:b2b,admin'
    //         ];

    //         $result['password'] = [
    //             'required', 
    //             'confirmed', 
    //             Rules\Password::defaults()->mixedCase()->letters()->numbers()->symbols()
    //         ];
    //     }

    //     if($action == 'update')
    //     {
    //         $result['email'][4] .= (',' . $input['id']);
    //     }
        
    //     return $result;
    }

    // public static function GetMessages($action, $input) {
    //     return [
    //         'last_name.required' => 'Numele trebuie completat.',
    //         'first_name.required' => 'Prenumele trebuie completat.',
    //         'email.required' => 'Adresa de email trebuie completată.',
    //         'email.email' => 'Adresa de email nu pare să fie o adresă de email corectă.',
    //         'email.unique' => 'Adresa de email este deja folosită de alt utilizator',
    //         'password.min' => 'Parola trebuie să fie de cel puțin 8 caractere și să conțină litere mari și mici, cifre și caractere speciale.',
    //     ];
    // }

    // public static function saveUserSettings($input) {
    //     return (new SaveSetting($input))
    //         ->SetSuccessMessage('Schimbare status cu success!')
    //         ->Perform();
    // }

    public static function doChangepassword($input, $user) {

        $user->update([
            'password' => \Hash::make($input['password'])
        ]);

        return $user;

    }

    // public static function updatePassword($input) {
    //     return (new Changepassword(
    //         $input,
    //         [
    //             'password' => [
    //                 'required', 
    //                 'confirmed', 
    //                 Rules\Password::defaults()->mixedCase()->letters()->numbers()->symbols()
    //             ]
    //         ]
    //     ))
    //         ->SetSuccessMessage('Schimbare parolă cu success!')
    //         ->Perform();
    // }

    // public static function updatePermissions($input) {
    //     return (new UpdatePermissions(
    //         $input,
    //     ))
    //         ->SetSuccessMessage('Setare permissiuni cu success!')
    //         ->Perform();
    // }

    // public static function updateStatus($input) {
    //     return (new UpdateStatus(
    //         $input,
    //     ))
    //         ->SetSuccessMessage('Setare status cu success!')
    //         ->Perform();
    // }

    // public function Activate() {
    //     $this->email_verified_at = \Carbon\Carbon::now();
    //     $this->activated_at = \Carbon\Carbon::now();
    //     $this->save();
    //     return $this;
    // }

    // public function Deactivate() {
    //     $this->email_verified_at = NULL;
    //     $this->activated_at = NULL;
    //     $this->save();
    //     return $this;
    // }

    // /**
    //  * Am un array de obiecte [ ['user_id' => 15], ....]
    //  * Returnez collection de User
    //  */
    // public static function GetUsersByIds($ids) {
        
    //     if( count($ids) == 0 )
    //     {
    //         return NULL;
    //     }

    //     $ids = collect($ids)->unique()->map(function($item) {
    //         return $item->user_id;
    //     })->sort()->toArray();

    //     return self::whereIn('id', $ids)->get();
    // }

    public static function byEmail($email) {
        return self::where('email', $email)->first();
    }

    // public function GetMyAdmins($all) {
    //     /**
    //      * A. Admini           - toti adminii 
    //      * $all = false ==> diferiti de cel care face upload
    //      */

    //     if($all)
    //     {
    //         $sql = "
    //             SELECT
    //                 `users`.id user_id
    //             FROM `users`
    //             LEFT JOIN `role_users` 
    //             ON `role_users`.user_id = `users`.id
    //             WHERE 
    //                 ((`role_users`.role_id = 1) OR (`role_users`.role_id = 2))
    //         ";
    //     }
    //     else
    //     {
    //         $sql = "
    //             SELECT
    //                 `users`.id user_id
    //             FROM `users`
    //             LEFT JOIN `role_users` 
    //             ON `role_users`.user_id = `users`.id
    //             WHERE 
    //                 ((`role_users`.role_id = 1) OR (`role_users`.role_id = 2))
    //                 AND (`users`.id <> " . $this-> id . ")
    //         ";
    //     }

    //     return \DB::select($sql);
    // }

    // public static function syncWithUserCustomer() {
    //     $users = self::all();
    //     foreach($users as $i => $user)
    //     {
    //         if($user->user_customers_count != $user->usercustomers->count())
    //         {
    //             $user->user_customers_count = $user->usercustomers->count();
    //             $user->save();
    //         }
    //     }
    // }

    /**
     * 19.08.2023 - daca rolul meu este in array-ul $slugs
     * Nu se aplica pe b2b (client)
     */
    public function inRoles($slugs) {
        if( (config('app.platform') == 'b2b') )
        {
            return true;
        }

        if(! $this->role)
        {
            return false;
        }

        return in_array($this->role->slug, $slugs);
    }
}
