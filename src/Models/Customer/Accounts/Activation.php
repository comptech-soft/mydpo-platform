<?php

namespace MyDpo\Models\Customer\Accounts;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\User;

class Activation extends Model {
   
    protected $table = 'accounts-activations';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'role_id' => 'integer',
        'activated' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'role_id',
        'token',
        'activated',
        'activated_at',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['customer']), __CLASS__))->Perform();
    }

    public static function byToken($token) {
        return self::where('token', $token)->where('activated', 0)->first();
    }

    public static function byUserAndCustomer($user_id, $customer_id, $role_id) {
        $record = self::where('user_id', $user_id)->where('customer_id', $customer_id)->first();

        if( ! $record)
        {
            $record = self::create([
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'token' => \Str::random(64),
                'role_id' => $role_id,
            ]);
        }
        else
        {
            $record->role_id = $role_id;
            $record->save();
        }

        return $record;
    }

    public static function RegisterActivation($user, $sender, $template, $payload) {
        if(!! $payload['customer_id'])
        {
            $activation = self::createActivation($payload['account']['user_id'], $payload['account']['customer_id'], $payload['account']['role_id']);
            return [
                'btn_url' => \MyDpo\Models\System\Platform::find(2)->url . '/' . \Str::replace('[token]', $activation->token, $template['btn_url']),
                'btn_caption' => $template['btn_caption'],
                'customer_name' => Customer::find($payload['account']['customer_id'])->name,
            ];
        }
        else
        {
            $activation = self::createActivation($user->id, NULL, $payload['role']->id);
            return [
                'btn_url' => \MyDpo\Models\System\Platform::find(1)->url . '/' . \Str::replace('[token]', $activation->token, $template['btn_url']),
                'btn_caption' => $template['btn_caption'],
                'customer_name' => NULL,
            ];
        }
    }

    public static function createActivation($user_id, $customer_id, $role_id) {
        $q = self::where('user_id', $user_id);
        if(!! $customer_id )
        {
            $q->where('customer_id', $customer_id);
        }
        $record = $q->first();

        if(!! $record )
        {
            $record->update(['activated' => 0, 'activated_at' => NULL, 'role_id' => $role_id]);
        }
        else
        {
            $record = self::create(['user_id' => $user_id, 'role_id' => $role_id, 'token' => \Str::random(64)]);
        }

        return $record;
    }
}