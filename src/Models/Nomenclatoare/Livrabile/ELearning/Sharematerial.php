<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Scopes\NotdeletedScope;


// use MyDpo\Models\CustomerCursUser;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Customer\ELearning\CustomerCurs;
use MyDpo\Models\Customer\Chestionare\CustomerChestionar;

// use MyDpo\Events\CustomerCurs\CursShare as CursShareEvent;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\DaysDifference;
use MyDpo\Traits\Numberable;

use MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Trimiteri\AtLeastOneCustomer;
use MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Trimiteri\AtLeastOneMaterial;

class Sharematerial extends Model {

    use Itemable, Actionable, Numberable, DaysDifference;

    protected $table = 'share-materiale';

    protected $casts = [
        'id' => 'integer',
        'customers' => 'json',
        'materiale_trimise' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'users_count' => 'integer',
        'cursuri_count' => 'integer',
        'customers_count' => 'integer',
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

        'tip_curs',
        'location',
        'data_curs',
        'ora_curs',
        'durata_curs',

        'platform',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',

        'sender_full_name',
        'users_count',
        'cursuri_count',
        'customers_count'
    ];

    protected $appends = [
        'ore_lucrate',
        'count_customers',
        'count_users',
        'count_materiale',
        'trimitere_type',
        'days_difference',
        'valabilitate',
    ];

    protected $types = [
        'onsite' => [
            'text' => 'Onsite',
        ],

        'online' => [
            'text' => 'Online',
        ],
    ];

    public $numberable = [
        'field' => 'number',
        'where' => "(type = '%%type%%')",
        'replacement' => [
            '%%type%%' => 'type',
        ],
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

    /**
     * ATTRIBUTES
     */
    public function getOreLucrateAttribute() {
        return is_null($this->effective_time) ? 0 : $this->effective_time; 
    }

    public function getCountCustomersAttribute() {
        return is_null($this->customers) ? 0 : count($this->customers);
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
        return is_null($this->materiale_trimise) ? 0 : count($this->materiale_trimise);
    }

    public function getTrimitereTypeAttribute() {
        $r = [];
        if(!! $this->tip_curs )
        {
            $r[] = $this->types[$this->tip_curs]['text'];
        }

        if(!! $this->location)
        {
            $r[] = $this->location;
        }

        if(!! $this->data_curs )
        {
            $r[] = \Carbon\Carbon::createFromFormat('Y-m-d', $this->data_curs)->format('d.m.Y');
        }

        if(!! $this->ora_curs )
        {
            $r[] = $this->ora_curs;
        }

        if(!! $this->durata_curs )
        {
            $r[] = $this->durata_curs;
        }

        return implode(', ', $r);
    }

    public function getValabilitateAttribute() {

        if(! $this->date_from && ! $this->date_to )
        {
            return 'Nelimitat';
        }

        if(!! $this->date_from && !! $this->date_to)
        {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_from)->format('d.m.Y') . ' - ' . \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_to)->format('d.m.Y');
        }

        if(!! $this->date_from)
        {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_from)->format('d.m.Y');        
        }

        return \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_to)->format('d.m.Y');        
    }

    /**
     * RELATIONS
     */

    public function details() {
        return $this->hasMany(SharematerialDetail::class, 'trimitere_id');
    }

    public function createdby() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public static function getItems($input) {

    //     if( array_key_exists('jsons', $input) && $input['jsons'])
    //     {
    //         $query = self::applyJsonsFilter(self::query(), $input['jsons']);
    //     }
    //     else
    //     {
    //         $query = self::query();
    //     }
        
    //     return (new GetItems($input, $query->with(['createdby']), __CLASS__))->Perform();
    // }

    // public static function applyJsonsFilter($query, $jsons) {

    //     foreach($jsons as $i => $json)
    //     {
    //         $query = self::applyJsonFilter($query, $json);
    //     }

    //     return $query;
    // }

    // public static function applyJsonFilter($query, $json) {
        
    //     return $query->{$json['method']}($json['column'] . '->' . $json['key']);
        
    // }

    



    // public static function CreateCustomerMaterialsRecords($type, $trimitere_id, $calculated_time, $customer_id, $users, $materiale_trimise) {
    //     foreach($materiale_trimise as $material_id)
    //     {
    //         call_user_func([__CLASS__, 'CreateCustomer' . ucfirst($type) . 'Record'], $trimitere_id, $calculated_time, $customer_id, $users, $material_id);
    //     }

    // }

    // public static function CreateCustomerCursRecord($trimitere_id, $calculated_time, $customer_id, $users, $material_id) {



    //     /**
    //      * Se face inregistrare pentru fiecare user in parte
    //      */
    //     foreach($users as $i => $user_id) 
    //     {

    //         
    //         event(new CursShareEvent([
    //             'customer' => $customercurs->customer,
    //             'curs' => $customercurs->curs,
    //             'receiver' => $customercurs->user,
    //         ]));
    //     }
    // }   

    

    // public static function CreateCustomerDetailsRecords($trimitere_id, $calculated_time, $customer_id, $users, $materiale_trimise) {
    //     foreach($users as $i => $user_id) {
    //         self::CreateCustomerUserDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $materiale_trimise);
    //     }
    // }

    // public static function CreateCustomerUserDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $materiale_trimise) {
    //     foreach($materiale_trimise as $i => $material_id) {
    //         self::CreateCustomerUserMaterialDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $material_id);
    //     }
    // }

    // public static function CreateCustomerUserMaterialDetailsRecords($trimitere_id, $calculated_time, $customer_id, $user_id, $material_id) {
    //     SharematerialDetail::create([
    //         'trimitere_id' => $trimitere_id,
    //         'customer_id' => $customer_id,
    //         'assigned_to' => $user_id,
    //         'sended_document_id' => $material_id,
    //         'effective_time' => $calculated_time,
    //         'created_by' => \Auth::user()->id,
    //         'platform' => config('app.platform'),
    //         'created_by' => \Auth::user()->id,

    //     ]);
    // }

    public static function doInsert($input, $record) {

        dd($input);
        
        $record = self::create([
            ...$input,
            'created_by' => \Auth::user()->id,
        ]);

        $record->Sync();

        $record->CreateDetailsRecords();

        $record->CreateCustomersMaterials();
        
        return $record;
    } 

    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update', 'asociere']) )
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
                'required',
                'array',
                'min:1',
                new AtLeastOneCustomer($action, $input),
            ],
            
            'materiale_trimise' => [
                'required',
                'array',
                'min:1',
                new AtLeastOneMaterial($action, $input),
            ],
        ];

        return $result;
    }

    public function CreateDetailsRecords() {

        $numberOfitems = $this->count_users * $this->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $this->effective_time/$numberOfitems : 0; 

        foreach($this->customers as $customer_id => $users)
        {
            foreach($users as $i => $user_id)
            {
                foreach($this->materiale_trimise as $j => $sended_document_id)
                {
                    $input = [
                        'trimitere_id' => $this->id,
                        'customer_id' => $customer_id,
                        'assigned_to' => $user_id,
                        'sended_document_id' => $sended_document_id,
                        'platform' => config('app.platform'),
                        'effective_time' => $calculated_time,
                    ];

                    SharematerialDetail::create($input);
                }
            }
        }
    }

    public function CreateCustomersMaterials() {
        
        $numberOfitems = $this->count_users * $this->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $this->effective_time/$numberOfitems : 0; 

        if($this->type == 'curs')
        {
            CustomerCurs::CreateRecordsByTrimitere($this);
        }

        if($this->type == 'chestionar')
        {
            CustomerChestionar::CreateRecordsByTrimitere($this);
        }
    }
    
    public static function SyncRecords() {
        $records = self::all();

        foreach($records as $i => $record)
        {
            $record->Sync();
        }
    }

    public function Sync() {
        $this->sender_full_name = $this->createdby->full_name;

        $this->cursuri_count = $this->count_materiale;
        $this->customers_count = $this->count_customers;
        $this->users_count = $this->count_users;

        $this->save();
    }

}
