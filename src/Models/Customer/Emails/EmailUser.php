<?php

namespace MyDpo\Models\Customer\Emails;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Models\Authentication\User;

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
        'sended_by',
        'props',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sended_by');
    }

    public static function RegisterUsersToSend($email, $users, $payload) {

        foreach($users as $i => $user_id)
        {
            self::RegisterUserToSend($email, $user_id, $payload);
        }

    }

    public static function RegisterUserToSend($email, $user_id, $payload) {

        self::create([
            'customer_email_id' => $email->id,
            'user_id' => $user_id,
            'template_id' => $email->template_id,
            'sended_by' => \Auth::user()->id,
            'sended_at' => NULL,
            'props' => [
                'template' => $email->props['template'],
                'payload' => $payload,
            ],
            'created_by' => \Auth::user()->id,
        ]);

    }

}