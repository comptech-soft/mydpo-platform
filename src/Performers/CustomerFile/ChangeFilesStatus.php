<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFile;

class ChangeFilesStatus extends Perform {

    public function SendNotify() {
        if( ($this->input['status'] == 'public') && ($record->status == 'protected'))
        {
            $customer = Customer::where('id', $record->customer_id)->with(['persons'])->first();

            $notificare = Notification::getByEntityAndAction('document', 'trimitere');

            foreach($customer->persons as $person)
            {
                $notificare->Notify([
                    'sender_id' => \Sentinel::check()->id,
                    'entity_id' => $record->id,
                    'customer_id' => $customer->id,
                    'receiver_id' => $person->user_id,
                    'date_from' => NULL,
                    'date_to' => NULL,
                    'readed_at' => NULL,
                    'status' => 'created',
                    'props' => [
                        'file' => $record,
                        'url' => '???',
                        'sender' => \Sentinel::check(),
                    ],
                    'created_by' => \Sentinel::check()->id,
                ]);
            }

        }
    }
    
    public function Action() {

        foreach($this->input['files'] as $i => $file_id)
        {
            $record = CustomerFile::find($file_id);

            $record->status = $this->input['status'];

            if(false)
            {
                $this->SendNotify();
            }

            $record->save();
        }

        activity()
            ->by(\Auth::user())
            ->on($record)
            ->withProperties(
                [
                    'input' => request()->all(),
                    'ip' => request()->ip(),
                ]
            )
            ->event(__CLASS__)
            ->createdAt($now = now())
            ->log(
                __(
                    ':name a schimbat statusul fișierelor', 
                    [
                        'name' => \Auth::user()->full_name 
                    ]
                ), 
            );
        
    }

} 