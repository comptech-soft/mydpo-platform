<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class Sharematerial extends Model {

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
        'completed_from',
        'completed_to',
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

    public function scopeNotdeleted($query) {
        return $query->whereRaw("(MOD(id, 2) = 0");
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
        return (new GetItems($input, self::query()->notdeleted(), __CLASS__))->Perform();
    }

}
