<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Customer\Centralizatoare\RowFileable;

use MyDpo\Performers\Customer\Registre\RowFile\UploadFiles;

class RowFile extends Model {
    
    use Itemable, Actionable, RowFileable;
    
    protected $table = 'customers-registers-rows-files';

    protected $casts = [
        'row_id' => 'integer',
        'file' => 'json',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'file',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'is_image', 
        'is_office',
        'is_pdf',
        'icon'
    ];

    public static function uploadFiles($input) {
        return (new UploadFiles($input))->Perform();
    }

}