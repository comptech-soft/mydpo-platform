<?php

namespace MyDpo\Models\Livrabile\Emails;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class TemplateEmail extends Model {
   
    use Itemable, Actionable;

    protected $table = 'email-templates';

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
        'subject',
        'platform',
        'body',
        'props',
        'deleted',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    /**
     * Trimierea unui calup de emailuri
     * Se primeste:
     *      id = id-ul template-ului de email
     *      customers = un array [6#123, 6#434, ....], unde structura este customer_id#user_id
     */
    public static function doSend($input, $record) {

        if(array_key_exists('customers', $input))
        {
            dd($input);
        }
    }

    // public static function findByEntityActionPlatform($entity, $action, $platform) {

    //     return self::where('entity', $entity)
    //         ->where('action', $action)
    //         ->where('platform', $platform)
    //         ->first();
            
    // }


}