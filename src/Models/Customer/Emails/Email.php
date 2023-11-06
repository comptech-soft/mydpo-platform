<?php

namespace MyDpo\Models\Customer\Emails;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;

use MyDpo\Traits\Itemable;

class Email extends Model {

    use Itemable;
    
    protected $table = 'customers-emails';

    protected $casts = [
        'props' => 'json',
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'template_id' => 'integer',
        'customer_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'template_id',
        'description',
        'props',
        'created_by',
        'updated_by',
    ];

    public static function RegisterToSend($template, $customer_id, $users, $payload) {

        $record = self::create([
            'customer_id' => $customer_id,
            'template_id' => $template->id,
            'descripton' => NULL,
            'props' => [
                'template' => collect($template->toArray())->only(['id', 'subject', 'body', 'name', 'props', 'before_method', 'btn_url', 'btn_caption'])->toArray(),
                'payload' => $payload,
            ],
            'created_by' => \Auth::user()->id,
        ]);

        $record->RegisterUsersToSend($users, $payload);

    }

    public function RegisterUsersToSend($users, $payload) {
        EmailUser::RegisterUsersToSend($this, $users, $payload);
    }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['orders.services.service.type', 'customer']), __CLASS__))->Perform();
    // }

}