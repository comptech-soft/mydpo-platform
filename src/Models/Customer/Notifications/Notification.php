<?php

namespace MyDpo\Models\Customer\Notifications;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Livrabile\Notifications\TemplateNotification;
use MyDpo\Models\Customer\Customer_base as Customer;
use MyDpo\Models\Authentication\User;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class Notification extends Model {

    use Itemable, Actionable;

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
        'receiver',
        'customer'
    ];

    protected $appends = [
        'icon',
        'noty_status'
    ];

    public function getIconAttribute() {
        if( ! $this->template )
        {
            return NULL;
        }

        return !! $this->template->props ? $this->template->props['icon'] : NULL;
    }

    public function getNotyStatusAttribute() {
        if($this->status == 'readed')
        {
            return [
                'color' => 'green',
                'icon' => 'mdi-checkbox-marked-circle',
                'text' => 'Citită',
            ];
        }
        return [
            'color' => 'red',
            'icon' => 'mdi-circle',
            'text' => 'Necitită',
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
    
    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public static function RegisterToSend($template, $customer_id, $users) {

        foreach($users as $i => $user_id)
        {
            self::RegisterUserToSend($template, $customer_id, $user_id);
        }

    }

    public static function RegisterUserToSend($template, $customer_id, $user_id) {

        self::create(dd([
            'type_id' => $template->id,
            // 'user_id' => $user_id,
            // 'template_id' => $email->template_id,
            // 'sended_by' => \Auth::user()->id,
            // 'sended_at' => NULL,
            // 'props' => [
            //     'template' => $email->props['template'],
            //     'created_by' => \Auth::user()->id,
            // ]
        ]));

    }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

}