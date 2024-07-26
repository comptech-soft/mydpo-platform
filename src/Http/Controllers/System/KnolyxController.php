<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Curs;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\ELearning\CustomerCursUser;
use MyDpo\Events\Knolyx\CursFinished;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {


        $q = [...$r->all()];
		
		// $q = [
			
		// 	"id" => "af46ec07-de79-40b5-916c-0058b54b2d2b", // unique id, messages with the same id should be ignored
		// 	"type" => "COURSE_COMPLETED",
		// 	"data" => [
		// 		"user" => [
		// 			"id" => 38232,
		// 			"name" => "test1 test2",
		// 			"email" => "mydpo@decalex.ro"
		// 		],
		// 		"course" => [
		// 			"id" => 2455,
		// 			"name" => "Intro to marketing",
		// 		]
		
		// 	],
		// ];
		
		if($q['type'] == 'COURSE_COMPLETED')
		{
			
			$user = User::where('k_id', $q['data']['user']['id'])->first();
			
			$curs = Curs::where('k_id', $q['data']['course']['id'])->first();

			$customer = NULL;
				
			$records = CustomerCursUser::where('user_id', $user->id)->where('curs_id', $curs->id)->get();

			foreach($records as $i => $record) 
			{
				$record->update([
					'status' => 'done',
					'done_at' => \Carbon\Carbon::now()->format('Y-m-d'),
				]);

				if( ! $customer )
				{
					$customer = Customer::find( $record->customer_id);
				}

				// \Log::info($user->id . '#' . $curs->id . '#' . $record->id . '#' . $record->status . '#' . $record->done_at);
			}

			// event(new CursFinished([
			// 	'customer' => $customer,
			// 	'curs' => $curs,
			// 	'receiver' => $user,
			// ]));
			
			// \Log::info(__METHOD__ . '== END ==' . __METHOD__);
			
			return __METHOD__;
		}

        $params = [
            "id" => $q['id'], //"af46ec07-de79-40b5-916c-0058b54b2d2b", // unique id, messages with the same id should be ignored
            "type" => "PING",
            "data" => [
                "message" => "PING"
            ]
        ];

        return $params;
    }

}