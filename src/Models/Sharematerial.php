<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Scopes\ShareMaterialScope;
use MyDpo\Traits\NextNumber;
use MyDpo\Rules\Sharematerial\AtLeastOneCustomer;
use MyDpo\Rules\Sharematerial\AtLeastOneMaterial;
use MyDpo\Models\SharematerialDetail;
use MyDpo\Models\CustomerCurs;
use MyDpo\Models\CustomerCursUser;

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

    public function details() {
        return $this->hasMany(SharematerialDetail::class, 'trimitere_id');
    }

    public static function getItems($input) {

        if( array_key_exists('jsons', $input) && $input['jsons'])
        {
            $query = self::applyJsonsFilter(self::query(), $input['jsons']);
        }
        else
        {
            $query = self::query();
        }
        
        return (new GetItems($input, $query, __CLASS__))->Perform();
    }

    public static function applyJsonsFilter($query, $jsons) {

        foreach($jsons as $i => $json)
        {
            $query = self::applyJsonFilter($query, $json);
        }

        return $query;
    }

    public static function applyJsonFilter($query, $json) {
        
        return $query->{$json['method']}($json['column'] . '->' . $json['key']);
        
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doInsert($input, $share) {

        $share = self::create($input);

        $share->CreateDetailsRecords();

        $share->CreateCustomersMaterials();

        return $share;
    } 

    public function CreateCustomersMaterials() {
        $numberOfitems = $this->count_users * $this->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $this->effective_time/$numberOfitems : 0; 

        foreach($this->customers as $customer_id => $users) {
            static::CreateCustomerMaterialsRecords($this->type, $this->id, $calculated_time, $customer_id, $users, $this->materiale_trimise);
        }
    }

    public static function CreateCustomerMaterialsRecords($type, $trimitere_id, $calculated_time, $customer_id, $users, $materiale_trimise) {
        foreach($materiale_trimise as $material_id)
        {
            call_user_func([__CLASS__, 'CreateCustomer' . ucfirst($type) . 'Record'], $trimitere_id, $calculated_time, $customer_id, $users, $material_id);
        }

    }

    public static function CreateCustomerCursRecord($trimitere_id, $calculated_time, $customer_id, $users, $material_id) {
        CustomerCurs::create([
            'customer_id' => $customer_id,
            'curs_id' => $material_id,
            'trimitere_id' => $trimitere_id,
            'effective_time' => $calculated_time * count($users),
            'assigned_users' => $users,
        ]);

        /**
         * Se face inregistrare pentru fiecare user in parte
         */
        foreach($users as $i => $user_id) 
        {
            CustomerCursUser::create([
                'customer_id' => $customer_id,
                'curs_id' => $material_id,
                'trimitere_id' => $trimitere_id,
                'user_id' => $user_id,
                'status' => 'sended',
            ]);
        }
    }   

    public function CreateDetailsRecords() {

        $numberOfitems = $this->count_users * $this->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $this->effective_time/$numberOfitems : 0; 

        foreach($this->customers as $customer_id => $users)
        {
            static::CreateCustomerDetailsRecords($this->id, $calculated_time, $customer_id, $users, $this->materiale_trimise);
        }
    }

    public static function CreateCustomerDetailsRecords($trimitere_id, $calculated_time, $customer_id, $users, $materiale_trimise) {
        foreach($users as $i => $user_id) {
            self::CreateCustomerUserDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $materiale_trimise);
        }
    }

    public static function CreateCustomerUserDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $materiale_trimise) {
        foreach($materiale_trimise as $i => $material_id) {
            self::CreateCustomerUserMaterialDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $material_id);
        }
    }

    public static function CreateCustomerUserMaterialDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $material_id) {
        SharematerialDetail::create([
            'trimitere_id' => $trimitere_id,
            'customer_id' => $customer_id,
            'assigned_to' => $user_id,
            'sended_document_id' => $material_id,
            'effective_time' => $calculated_time,
            'created_by' => \Auth::user()->id,

        ]);
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
