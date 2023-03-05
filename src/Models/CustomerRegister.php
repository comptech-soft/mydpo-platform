<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\NextNumber;
use MyDpo\Performers\CustomerRegister\ExportRegister;

class CustomerRegister extends Model {

    use NextNumber;

    protected $table = 'customers-registers';

    protected $casts = [
        'props' => 'json',
        'columns' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'responsabil_nume',
        'responsabil_functie',
        'customer_id',
        'register_id',
        'columns',
        'number',
        'date',
        'status',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public $nextNumberColumn = 'number';

    public function getRealColumnsAttribute() {
        dd(__METHOD__);
    }

    function rows() {
        return $this->hasMany(CustomerRegistruRow::class, 'customer_register_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['rows.values']), __CLASS__))->Perform();
    }

    public static function doDelete($input, $record) {

        $rows = CustomerRegistruRow::where('customer_register_id', $record->id)->get();

        foreach($rows as $i => $row)
        {
            $row->deleteRowWithValues();
        }

        $record->delete();

        return $record;
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
            'customer_id' => 'required|exists:customers,id',
            'register_id' => 'required|exists:registers,id',
            'number' => 'required',
            'date' => [
                'required',
                'date',
                // new UniqueOrderService($input),
            ],
        ];
        return $result;
    }

    public static function registerDownload($input) {
        return (new ExportRegister($input))->Perform();
    }

    
    public static function nextNumberWhere($input) {
        return "(customer_id = " . $input['customer_id'] . ") AND (register_id = " . $input['register_id'] . ")";
    }

}