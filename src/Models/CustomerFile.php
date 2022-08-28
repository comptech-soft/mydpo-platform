<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFile extends Model {

    
    protected $table = 'customers-files';

    // protected $appends  = ['icon', 'is_image', 'is_office', 'just_name'];

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'folder_id' => 'integer',
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'folder_id',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'file_size',
        'file_width',
        'file_height',
        'name',
        'deleted',
        'platform',
        'description',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

}