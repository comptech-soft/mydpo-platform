<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Sharematerial;

class SharematerialDetail extends Model {

    protected $table = 'share-materiale-details';

    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'trimitere_id' => 'integer',
        'assigned_to' => 'integer',
        'sended_document_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'effective_time' => 'float',
    ];

    protected $fillable = [
        'id',
        'trimitere_id',
        'customer_id',
        'assigned_to',
        'sended_document_id',
        'type',
        'status',
        'props',
        'effective_time',
        'platform',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function sharematerial() {
        return $this->belongsTo(Sharematerial::class, 'customer_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}