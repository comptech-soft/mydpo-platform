<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolderPermission;
use MyDpo\Models\CustomerFolder;

class SaveFoldersAccess extends Perform {

    public function Action() {

        CustomerFolderPermission::where('customer_id', $this->input['customer_id'])
            ->where('user_id',  $this->input['user_id'])
            ->update([
                'has_access' => 0,
            ]);

        if( ! array_key_exists('selectedFolders', $this->input ) )
        {
            $this->input['selectedFolders'] = [];
        }

        foreach($this->input['selectedFolders'] as $i => $folder_id) 
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