<?php

namespace MyDpo\Models\Customer\Emails;

use Illuminate\Database\Eloquent\Model;

class EmailUser extends Model {

    protected $table = 'customers-emails-users';

    protected $casts = [
        'props' => 'json',
        'id' => 'integer',
        'customer_email_id' => 'integer',
        'user_id' => 'integer',
        'template_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_email_id',
        'user_id',
        'template_id',
        'sended_at',
        'props',
    ];

}