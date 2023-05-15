<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Rules\CustomerDepartment\UniqueName;
use MyDpo\Traits\Itemable;

class CustomerDepartment extends Model {

    use Itemable;
    
    protected $table = 'customers-departamente';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'departament',
        'nume_responsabil',
        'numar_angajati',
        'telefon',
        'email',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $withCount = [
        'accounts'
    ];

    function accounts() {
        return $this->hasMany(CustomerAccount::class, 'department_id');
    }

    public static function CreateIfNecessary($customer_id, $new_customer_id, $department_id) {

        $record = self::find($department_id);

        $exists = self::where('customer_id', $new_customer_id)->where('departament', $record->departament)->first();

        if(! $exists )
        {
            $exists = self::create([
                'customer_id' => $new_customer_id,
                'departament' => $record->departament,
            ]);
        }
    
        return $exists; 

    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'departament' => [
                'required', 
                new UniqueName($input),
            ],
            'email' => 'sometimes|nullable|email',
            'customer_id' => 'exists:customers,id',
        ];

        return $result;
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}