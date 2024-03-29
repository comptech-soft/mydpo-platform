<?php

namespace MyDpo\Models\Customer\Departments;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Rules\Customer\Entities\Department\UniqueName;

use MyDpo\Traits\Itemable;

use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Customer\Planuriconformare\Planconformare;
use MyDpo\Models\Customer\Registre\Registru;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;

class Department extends Model {

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
        return $this->hasMany(Account::class, 'department_id');
    }

    public static function CreateIfNotExists($customer_id, $name) {
        $record = self::where('customer_id', $customer_id)->where('departament', $name)->first();

        if(! $record)
        {
            $record = self::create([
                'customer_id' => $customer_id,
                'departament' => $name,
            ]);
        }

        return $record;
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

    public static function doDelete($input, $record) {

        Account::where('department_id', $record->id)->update(['department_id' => NULL]);
        Planconformare::where('department_id', $record->id)->update(['department_id' => NULL]);
        Registru::where('department_id', $record->id)->update(['department_id' => NULL]);
        Registru::where('department_id', $record->id)->update(['department_id' => NULL]);

        $record->delete();

        return $record;
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}