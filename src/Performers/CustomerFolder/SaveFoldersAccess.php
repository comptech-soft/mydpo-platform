<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolderPermission;

class SaveFoldersAccess extends Perform {

    public function Action() {

        CustomerFolderPermission::where('customer_id', $this->input['customer_id'])
            ->where('user_id',  $this->input['user_id'])
            ->update([
                'has_access' => 0,
            ]);

        foreach($this->input['selectedFolders'] as $i => $folder_id) 
        {
            $record = CustomerFolderPermission::where('customer_id', $this->input['customer_id'])
                ->where('user_id',  $this->input['user_id'])
                ->where('folder_id',  $folder_id)->first();

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
                    'customer_id' => $this->input['customer_id'],
                    'user_id' => $this->input['user_id'],
                    'folder_id' => $folder_id,
                    'created_by' => \Auth::user()->id,
                ]);
            }
        }

    }
}