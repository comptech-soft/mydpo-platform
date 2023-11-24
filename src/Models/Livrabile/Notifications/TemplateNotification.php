<?php

namespace MyDpo\Models\Livrabile\Notifications;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

use MyDpo\Models\Customer\Notifications\Notification;

class TemplateNotification extends Model {
   
    use Itemable, Actionable;

    protected $table = 'notification-types';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'entity',
        'action',
        'title',
        'platform',
        'message',
        'props',
        'deleted',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Trimierea unui calup de notificari
     * Se primeste:
     *      id = id-ul template-ului de notificare
     *      customers = un array [6#123, 6#434, ....], unde structura este customer_id#user_id
     */
    public static function doSend($input, $record) {

        if(array_key_exists('customers', $input))
        {
            /**
             * Se inregistreaza notificarile pentru a fi trimise
             */
            dd($input['customers']);

            $record->RegisterCustomersNotificationsToSend(self::PrepareCustomersToSend($input['customers']));
        }
    }

    /**
     * Se inregistreaza notificarile pentru customer in vederea trimiterii
     */
    public function RegisterCustomersNotificationsToSend($customers) {
        foreach($customers as $customer_id => $users)
        {
            Notification::RegisterToSend($this, $customer_id, $users);
        }
    }

    /**
     * Din [customer_id#user_id, ....] se obtine [ {customer_id: [user_id, ...]}, ...]
     */
    private static function PrepareCustomersToSend($input) {
        $customers = [];

        foreach($input as $i => $item)
        {
            [$customer_id, $user_id] = explode('#', $item);

            if(! array_key_exists($customer_id, $customers) )
            {
                $customers[$customer_id] = [];
            }

            $customers[$customer_id][] = $user_id;
        }

        return $customers;
    }

    public static function FindByName($name) {
        return self::whereName($name)->first();
    }
}