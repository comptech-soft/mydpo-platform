<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;
use MyDpo\Models\Customer\Accounts\Account;

trait Centralizatorable {

    /**
     * Atributul visible pentru interfata
     */
    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    /**
     * Atributul Headerul pentru tabel
     */
    public function getTableHeadersAttribute() {
        return $this->columns_tree;
    }
    
    /**
     * Relatie catre departament
     */
    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->select(['id', 'departament']);
    }

    /**
     * Crearea unui document
     */
    public static function doInsert($input, $record) {
        
        $tip = self::GetTip($input);
       
        $record = self::create([
            ...$input,

            'columns_tree' => $tip->columns_tree,
            'columns_items' => $tip->columns_items,
            'columns_with_values' => $tip->columns_with_values,

            'nr_crt_column_id' => $tip->has_nr_crt_column,
            'visibility_column_id' => $tip->has_visibility_column,
            'status_column_id' => $tip->has_status_column,
            'department_column_id' => $tip->has_department_column,
            'files_column_id' => $tip->has_files_column,

            'current_columns' => $tip->columns->toArray(), 
        ]);

        /**
         * Se trimite notificare
         */
        if($input['visibility'] == 1)
        {
            self::SendNotification($input, $tip, $record);
        }

        return $record;
    }

    /**
     * Editarea unui document
     */
    public static function doUpdate($input, $record) {
        $record->update($input);

        /**
         * Se trimite notificare
         */
        $tip = self::GetTip($input);

        if($input['visibility'] == 1)
        {
            self::SendNotification($input, $tip, $record);
        }

        return $record;
    }

    public static function SendNotification($input, $tip, $record) {

        $notification_type_name = self::GetNotificationTypeName($input);

        $link = self::GetNotificationLink($input, $tip);

        $receivers = self::GetReceivers($input['customer_id']);

        event(new \MyDpo\Events\Customer\Livrabile\Centralizatorable\InsertDocument($notification_type_name, [
            'tip' => $tip->name,
            'numar' => $record->number,
            'data' => \Carbon\Carbon::createFromFormat('Y-m-d', $record->date)->format('d.m.Y'),
            'customers' => $receivers,
            'link' => $link,
        ]));
    }

    public static function GetReceivers($customer_id) {

        $sql = "SELECT id, user_id FROM `customers-persons` WHERE (customer_id = " . $customer_id . ") AND (user_id <> " . \Auth::user()->id . ")";
    
        $accounts = collect(\DB::select($sql));

        return $accounts->map(function($item) use ($customer_id) {
            return $customer_id . '#' . $item->user_id;
        })->toArray();

    }

    public static function GetNotificationLink($input, $tip) {
        /**
         * Link
         * 
         * http://mydpo-admin.local/registre-list/registre/2/80#/
         * http://mydpo-admin.local/registre-list/gap/2/85#/
         * 
         * http://mydpo-admin.local/centralizatoare-list/centralizatoare/2/117#/
         * http://mydpo-admin.local/centralizatoare-list/gap/2/117#/
         */
        if(array_key_exists('register_id', $input))
        {
            if($tip->on_registre_page == 1)
            {
                return '/registre-list/registre/' . $input['customer_id'] . '/' . $tip->id; 
            }
            
            return '/registre-list/gap/' . $input['customer_id'] . '/' . $tip->id; 
        }

        if($tip->on_gap_page == 1)
        {
            return '/centralizatoare-list/centralizatoare/' . $input['customer_id'] . '/' . $tip->id; 
        }
        
        return '/centralizatoare-list/gap/' . $input['customer_id'] . '/' . $tip->id;

    }

    public static function GetNotificationTypeName($input) {
        return array_key_exists('register_id', $input) ? 'registru.trimitere' : 'centralizator.trimitere';
    }

    /**
     * Duplicarea unui element
     */
    public static function doDuplicate($input, $record) {

        return self::Duplicate($input);

    }

    /**
     * Stergerea unui document
     */
    public static function doDelete($input, $record) {
        $record->DeleteRows();
        $record->delete();
        return $record;
    }
    
    /**
     * Generarea inregistrarilor
     */
    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

}