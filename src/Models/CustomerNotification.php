<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\TemplateNotification;
use MyDpo\Models\User;
use MyDpo\Models\Customer;
// use MyDpo\Models\RoleUser;
// use MyDpo\Rules\CustomerAccount\UniqueUser;

class CustomerNotification extends Model {

    protected $table = 'customers-notifications';

    protected $casts = [
        'props' => 'json',
        'id' => 'integer',
        'type_id' => 'integer',
        'customer_id' => 'integer',
        'subject_id' => 'integer',
        'receiver_id' => 'integer',
        'sender_id' => 'integer',
        'updated_by' => 'integer',
        'created_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'type_id',
        'subject_type',
        'subject_id',
        'sender_id',
        'customer_id',
        'receiver_id',
        'event',
        'date_from',
        'date_to',
        'readed_at',
        'message',
        'status',
        'payload',
        'props',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $with = [
        'sender', 
        'customer'
    ];

    protected $appends = [
        'icon',
        'noty_status'
    ];

    public function getIconAttribute() {
        return $this->template->props['icon'];
    }

    public function getNotyStatusAttribute() {
        if($this->status == 'readed')
        {
            return [
                'color' => 'green',
                'icon' => 'mdi-checkbox-marked-circle',
                'text' => 'Citit',
            ];
        }
        return [
            'color' => 'red',
            'icon' => 'mdi-circle',
            'text' => 'Necitit',
        ];
    }

    public function template() {
        return $this->belongsTo(TemplateNotification::class, 'type_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doUpdate($input, $account) {

    //     $account->update($input);

    //     $roleUser = RoleUser::CreateAccountRole(
    //         $input['customer_id'], 
    //         $input['user_id'], 
    //         $input['user']['role_id']
    //     );

    //     return $account;
    // }

    // public static function doInsert($input) {

    //     $account = self::create([
    //         'customer_id' => $input['customer_id'],
    //         'user_id' => $input['user_id'],
    //         'department_id' => $input['department_id'],
    //         'newsletter' => $input['newsletter'],
    //         'locale' => $input['locale'],
    //     ]);

    //     $roleUser = RoleUser::CreateAccountRole(
    //         $input['customer_id'], 
    //         $input['user_id'], 
    //         $input['user']['role_id']
    //     );

    //     return $account;

    // } 

    // public static function GetRules($action, $input) {
       
    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'customer_id' => 'required|exists:customers,id',
    //         'department_id' => 'required|exists:customers-departamente,id', 
    //         'user_id' => [
    //             'required',
    //             'exists:users,id',
    //             new UniqueUser($input),
    //         ],
    //         'user.role_id' => 'required|in:4,5'         
    //     ];

    //     return $result;
    // }

    

}