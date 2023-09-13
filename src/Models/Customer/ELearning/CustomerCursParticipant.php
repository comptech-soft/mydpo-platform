<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Exports\Customer\Livrabile\ELearning\Participant\Exporter;
use MyDpo\Imports\Customer\Livrabile\ELearning\Participant\Importer;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;

class CustomerCursParticipant extends Model {

    use Itemable, Actionable, Exportable, Importable;

    protected $table = 'customers-cursuri-participants';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_curs_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_curs_id',
        'customer_id',
        'platform',
        'last_name',
        'first_name',
        'functiie',
        'data',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    protected static function GetImporter($input) {
        return new Importer($input); 
    }

    public static function AfterAction($action, $input, $payload) {
        CustomerCurs::Sync($input['customer_id']);
    }

}