<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Scopes\ShareMaterialScope;
use MyDpo\Traits\NextNumber;
use MyDpo\Rules\Sharematerial\AtLeastOneCustomer;
use MyDpo\Rules\Sharematerial\AtLeastOneMaterial;

class Sharematerial extends Model {

    use NextNumber;

    protected $table = 'share-materiale';

    protected $casts = [
        'id' => 'integer',
        'customers' => 'json',
        'materiale_trimise' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'effective_time' => 'float',
        'ore_lucrate' => 'float',
    ];

    protected $fillable = [
        'id',
        'number',
        'date',
        'status',
        'type',
        'date_from',
        'date_to',
        'effective_time',
        'descriere',
        'customers',
        'materiale_trimise',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'ore_lucrate',
        'count_customers',
        'count_users',
        'count_materiale',
    ];

    public $nextNumberColumn = 'number';

    public static function nextNumberWhere($input) {
        return "type = '" . $input['type'] . "'";
    }

    protected static function booted() {
        static::addGlobalScope( new ShareMaterialScope );
    }

    /**
     * 
     * ATTRIBUTES
     * 
     */
    public function getOreLucrateAttribute() {
        return is_null($this->effective_time) ? 0 : $this->effective_time; 
    }

    public function getCountCustomersAttribute() {
        if(is_null($this->customers))
        {
            return 0;
        }
        return count($this->customers);
    }

    public function getCountUsersAttribute() {
        if(is_null($this->customers))
        {
            return 0;
        }
        $t = 0;
        foreach($this->customers as $customer_id => $users)
        {
            $t += count($users);
        }
        return $t;
    }

    public function getCountMaterialeAttribute() {
        if(is_null($this->materiale_trimise))
        {
            return 0;
        }
        return count($this->materiale_trimise);
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'number' => [
                'required'
            ],
            'date' => [
                'required',
                'date',
            ],
            'type' => 'in:centralizator,chestionar,curs',
            'effective_time' => [
                'numeric',
                'min:0',
            ],
            'customers' => [
                new AtLeastOneCustomer($input),
            ],
            'materiale_trimise' => [
                new AtLeastOneMaterial($input),
            ],
        ];

        return $result;
    }

    

}
