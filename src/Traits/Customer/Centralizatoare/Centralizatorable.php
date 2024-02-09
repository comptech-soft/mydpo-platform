<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;

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

        if($input['visibility'] == 1)
        {
            
            if(array_key_exists('register_id', $input))
            {
                $notification_type_name = 'registru.trimitere';

                if($tip->on_registre_page == 1)
                {
                    $link = 'registre-list/registre/' . $input['customer_id'] . '/' . $tip->id; 
                }
                else
                {
                    $link = 'registre-list/gap/' . $input['customer_id'] . '/' . $tip->id; 
                }
            }
            else
            {
                $notification_type_name = 'centralizator.trimitere';
            }
            /**
             * Link
             * 
             * http://mydpo-admin.local/registre-list/registre/2/80#/
             * http://mydpo-admin.local/registre-list/gap/2/85#/
             * 
             * http://mydpo-admin.local/centralizatoare-list/centralizatoare/2/117#/
             * http://mydpo-admin.local/centralizatoare-list/gap/2/117#/
             */
            /**
             * Se trimite notificare
             */
            event(new \MyDpo\Events\Customer\Livrabile\Centralizatorable\InsertDocument($notification_type_name, [
                'tip' => $tip->name,
                'document' => $record->number . ' / ' . $record->date,
                'customers' => [$input['customer_id'] . '#' . \Auth::user()->id],
                'link' => '/' . 'aaaaa' . '/' . $input['customer_id'],
            ]));



            // event(new \MyDpo\Events\Customer\Livrabile\Centralizatorable\InsertDocument($notification_type_name, [
            //     // 'nume_fisier' => $record->file_original_name,
            //     // 'nume_folder' => $record->folder->name,
            //     // 'customers' => self::CreateUploadReceivers($input['customer_id'], $input['folder_id']), 
            //     // 'link' => '/' . $record->folder->page_link . '/' . $input['customer_id'],
            // ]));
        }

        return $record;
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