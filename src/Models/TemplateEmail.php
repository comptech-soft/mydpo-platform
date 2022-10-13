<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateEmail extends Model {
   
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

    public static function findByEntityActionPlatform($entity, $action, $platform) {

        return self::where('entity', $entity)
            ->where('action', $action)
            ->where('platform', $platform)
            ->first();
            
    }


}