<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Traits\Itemable;
use MyDpo\Models\TemplateNotification;
use MyDpo\Models\User;
use MyDpo\Models\Customer;

class CustomerNotification extends Model {

    use Itemable;

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

}