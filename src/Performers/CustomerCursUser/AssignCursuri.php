<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;
use MyDpo\Models\Sharematerial;
use MyDpo\Models\SharematerialDetail;

class AssignCursuri extends Perform {

    public function Action() {

        $number = Sharematerial::getNextNumber([
            'type' => 'curs',
        ])['payload'];

        $shareinput = [
            'number' => $number,
            'date' => \Carbon\Carbon::now()->format('Y-m-d'),
            'status' => 'b2b',
            'type' => 'curs',
            'date_from' => $this->input['date_from'],
            'date_to' => $this->input['date_to'],
            'materiale_trimise' => $this->input['materiale_trimise'],
            'customers' => $this->input['customers'],
            'created_by' => \Auth::user()->id,
            'tip_curs' =>  $this->input['tip_curs'],
            'location' =>  $this->input['location'],
            'data_curs' =>  $this->input['data_curs'],
            'ora_curs' =>  $this->input['ora_curs'],
            'durata_curs' =>  $this->input['durata_curs'],
            'platform' => config('app.platform'),
        ];
        
        $trimitere = Sharematerial::create($shareinput);

        $trimitere->CreateDetailsRecords();
        $trimitere->CreateCustomersMaterials();

    }
}