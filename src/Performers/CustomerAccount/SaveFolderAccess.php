<?php

namespace MyDpo\Performers\CustomerAccount;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolderPermission;
use MyDpo\Models\CustomerFolder;

class SaveFolderAccess extends Perform {

    public function Action() {

        $type = $this->input['key'];

        $folders = CustomerFolderPermission::query()->leftJoin(
            'customers-folders',
            function($j) {
                $j->on('customers-folders.id', '=', 'customers-folders-permissions.folder_id');
            }
        )
        ->where('customers-folders-permissions.customer_id', $this->input['customer_id'])
        ->where('customers-folders-permissions.user_id',  $this->input['user_id'])
        ->where('customers-folders.type',  $type)
        ->update([
            'has_access' => 0,
        ]);

        dd($folders);

        if( ! array_key_exists('selected', $this->input ) )
        {
            $this->input['selected'] = [];
        }


        foreach($this->input['selected'] as $i => $folder_id) 
        {
            $this->MakeAccess($folder_id, $this->input['customer_id'], $this->input['user_id']);
        }
    
    }

    public function MakeAccess($folder_id, $customer_id, $user_id) {

        $record = CustomerFolderPermission::where('customer_id', $customer_id)
            ->where('user_id',  $user_id)
            ->where('folder_id',  $folder_id)
            ->first();

        if(!! $record)
        {
            $record->update([
                'has_access' => 1,
                'updated_by' => \Auth::user()->id,
            ]);
        }
        else
        {
            CustomerFolderPermission::create([
                'has_access' => 1,
                'customer_id' => $customer_id,
                'user_id' => $user_id,
                'folder_id' => $folder_id,
                'created_by' => \Auth::user()->id,
            ]);
        }

        $folder = CustomerFolder::find($folder_id);

        if(!! $folder->parent_id)
        {
            $this->MakeAccess($folder->parent_id, $customer_id, $user_id);
        }

    }
}