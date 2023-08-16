<?php

namespace MyDpo\Models\Livrabile\Notifications;

use Illuminate\Database\Eloquent\Model;

class TemplateNotification extends Model {
   
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

    public static function findByEntityActionPlatform($entity, $action, $platform) {

        return self::where('entity', $entity)
            ->where('action', $action)
            ->where('platform', $platform)
            ->first();
            
    }


}