<?php

namespace MyDpo\Models\Livrabile\Contracts;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Livrabile\Service\UniqueName;
use MyDpo\Models\ServiceType;

class Service extends Model {

    use Itemable, Actionable;

    protected $table = 'services';

    protected $types = [
        'service' => [
            'name' => 'Service',
            'color' => 'cyan',
        ],

        'subscription' => [
            'name' => 'Abonament',
            'color' => 'purple',
        ],
    ];

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'tarif' => 'decimal:2',
        'tarif_ore_suplimentare' => 'decimal:2',
        'ore_incluse_abonament' => 'integer',
        'abonamente' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'type',
        'order_no',
        'tarif',
        'tarif_ore_suplimentare',
        'ore_incluse_abonament',
        'is_abonament',
        'abonamente',
        'others',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'service_type',
    ];

    public function getServiceTypeAttribute() {
        return $this->types[$this->type];
    }
    public function type() {
        return $this->belongsTo(ServiceType::class, 'type', 'slug');
    }

    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
                'max:191',
                new UniqueName($action, $input),
            ],

            'type' => 'exists:services-types,slug',
        ];

        return $result;
    }

}