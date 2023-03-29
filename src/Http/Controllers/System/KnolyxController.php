<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\User;
use MyDpo\Models\Curs;
use MyDpo\Models\CustomerCursUser;
use MyDpo\Events\Knolyx\CursFinished;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {


        $q = [...$r->all()];
		
		$user = User::where('k_id', $q['data']['user']['id'])->first();
		
		$curs = Curs::where('k_id', $q['data']['course']['id'])->first();
			
		$records = CustomerCursUser::where('user_id', $user->id)->where('curs_id', $curs->id)->get();

        foreach($records as $i => $record) 
        {
            $record->update([
                'status' => 'done',
                'done_at' => \Carbon\Carbon::now()->format('Y-m-d'),
            ]);

            \Log::info($user->id . '#' . $curs->id . '#' . $record->id . '#' . $record->status . '#' . $record->done_at);
        }

        event(new CursFinished([
            'customer' => NULL,
            'curs' => $curs,
            'receiver' => $user,
        ]));

        $params = [
            "id" => "af46ec07-de79-40b5-916c-0058b54b2d2b", // unique id, messages with the same id should be ignored
            "type" => "PING",
            "data" => [
                "message" => "PING"
            ]
        ];

        return $params;
    }

}

