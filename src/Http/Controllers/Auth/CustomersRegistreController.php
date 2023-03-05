<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerRegister;
use MyDpo\Models\CustomerDepartment;

class CustomersRegistreController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-registre/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerRegister::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerRegister::doAction($action, $r->all());
    }

    public function getNextNumber(Request $r) {
        return CustomerRegister::getNextNumber($r->all());
    }

    public function registerDownloadPreview($id) {

        $registru = CustomerRegister::where('id', $id)->first();

        $valuecolumns = [];
        foreach($registru->columns as $i => $column)
        {
            if($column['column_type'] == 'single')
            {
                $valuecolumns[$column['id']] = [
                    'column' => $column,
                    'value' => NULL,
                ];
            }
            else
            {
                if( array_key_exists('children', $column) )
                {
                    foreach($column['children'] as $j => $child)
                    {
                        $valuecolumns[$child['id']] = [
                            'column' => $child,
                            'value' => NULL,
                        ];;
                    }
                }
            }
        }

        $records = [];
        foreach($registru->rows as $i => $row)
        {

            dd($row->myvalues);

            $values = [];
            foreach($row->values()->get() as $j => $item) 
            {
                $values[$item->column_id] = $item->value;
            }

            $record = [];

            foreach($valuecolumns as $column_id => $column)
            {
                $record[$column_id] = $values[$column_id];

                if($column['column']['type'] == 'O')
                {
                    $options = [];
                    foreach($column['column']['props']['options'] as $k => $option)
                    {

                        $options[$option['value']] = $option['text'];
                    }

                    $record[$column_id] = $options[$record[$column_id]];
                }
                else
                {
                    if($column['column']['type'] == 'departament')
                    {
                        $record[$column_id] = CustomerDepartment::find($record[$column_id])->departament;

                    }
                }

            }

            $records[$row->id] = $record;

        }

        

       
        return view('exports.customer-register.export', [
            'columns' => $registru->columns,
            'records' => $records,
            'children' => collect($registru->columns)->filter( function($item) {

                if($item['column_type'] != 'group')
                {
                    return FALSE;
                }
                if( ! array_key_exists('children', $item) )
                {
                    return FALSE;
                }
    
                if( count($item['children']) == 0)
                {
                    return FALSE;
                }
    
                return TRUE;
    
            })->toArray(),
        ]);


        
    }

    public function registerDownload(Request $r) {
        return CustomerRegister::registerDownload($r->all());
    }

}