<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\User;
use MyDpo\Models\CustomerDepartment;

class CustomerAccount extends Model {

    protected $table = 'customers-persons';

    protected $casts = [
        'props' => 'json',
        'id' => 'integer',
        'newsletter' => 'integer',
        'customer_id' => 'integer',
        'user_id' => 'integer',
        'order_no' => 'integer',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'phone',
        'department',
        'newsletter',
        'locale',
        'customer_id',
        'user_id',
        'props',
        'order_no',
        'created_by',
        'updated_by',
        'deleted',
        'deleted_by',
    ];

    protected $with = ['user', 'department'];

    protected $appends = ['role'];

    public function getRoleAttribute() {
        $r = NULL;
        foreach($this->user->roles as $i => $role)
        {
            if($role->type == 'b2b' )
            {
                $r = $role;
            }
        }
        return $r;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {
       
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'department_id' => 'required|exists:customers-departamente,id', 
            'user_id' => [
                'required',
                'exists:users,id'
            ],         
        ];

        // if($input['personSource'] == 1) // new person
        // {
        //     $result['user.last_name'] = 'required';
        //     $result['user.first_name'] = 'required';
        //     $result['user.email'] = 'required|email|unique:users,email';

        //     if($action == 'insert' || $action == 'duplicate')
        //     {
        //         $result['user.password'] = [
        //             'required', 
        //             'min:8', 
        //             'confirmed',
        //             new ValidPassword($input['user']['password']),
        //         ];
        //     }
        // }

        // if($input['personSource'] == 2) // existent person
        // {
        //     $result['user.id'] = [
        //         'required',
        //         new CustomerPerson($input),
        //     ];
        // }
        
        // if($action == 'update')
        // {
        //     $result['user.email'] .= (',' . $input['user']['id']);
        // }

        return $result;
    }



}