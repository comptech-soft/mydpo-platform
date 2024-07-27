<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Curs;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\ELearning\CustomerCursUser;
use MyDpo\Events\Customer\Livrabile\Cursuri\CursFinished;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {
		
		// inregistrarea din DB
		$token = \MyDpo\Models\System\SysConfig::where('code', 'knolyx-webhook-security-token')->first();
		
		// headerul X-Webhook-Security-Token din request 
		$value = $r->header('X-Webhook-Security-Token');
			
		// verificarea
		if(! $token || ! $value || $token->value != $value)
		{
			return response("Solicitare neautorizata. Nu sunt permisiuni.", 403);
		}
			
		$q = [...$r->all()];
		
		if($q['type'] == 'COURSE_COMPLETED')
		{
			
			$user = User::where('k_id', $q['data']['user']['id'])->first();
			
			$curs = Curs::where('k_id', $q['data']['course']['id'])->first();

			$customer = NULL;
				
			$records = CustomerCursUser::where('user_id', $user->id)->where('curs_id', $curs->id)->get();

			foreach($records as $i => $record) 
			{
				// se marcheaza cursul ca finalizat
				$record->update([
					'status' => 'done',
					'done_at' => \Carbon\Carbon::now()->format('Y-m-d'),
				]);
				
				// se afla clientul
				if( ! $customer )
				{
					$customer = Customer::find( $record->customer_id);
				}

				// 
			}
			
			if(!! $customer)
			{
				// se declanseaza un eveniment notificare

				$receivers = self::GetReceivers($customer->id);

				event(new CursFinished('knolyx.course-completed', [
					'name' => $curs->name,
				
					'customers' => [$customer->id . '#' . $user->id], //self::CreateUploadReceivers($record->customer_id, $record->folder_id), 

					'link' => '/customer-my-elearning/' . $customer->id,
				]));
			}
			
			
			
			return response('Evenimentul COURSE_COMPLETED (' . $q['id'] . ') înregistrat cu succes.', 200);
		}

        return response("Tipul eveniment (" . $q['type'] . ") nu este tratat.", 403);
    }

	public static function GetReceivers($customer_id) {

        $sql = "
            SELECT 
                user_id 
            FROM `customers-persons` 
            WHERE 
                (customer_id = " . $customer_id . ") AND 
                (user_id <> " . (Auth::user() ? \Auth::user()->id : '0') . ")
        ";

        $accounts = \DB::select($sql);
       
        $sql = "
            SELECT
                id as user_id
            FROM `users`
            WHERE
                (type = 'admin') AND 
                (id <> " . (Auth::user() ? \Auth::user()->id : '0') . ")
        ";
        
        $admins = \DB::select($sql);

        $receivers = [];
        
        foreach([...$accounts, ...$admins] as $i => $item)
        {
            if(! in_array($item->user_id, $receivers) )
            {
                $receivers[] = $item->user_id;
            }
        }

        $users = User::whereIn('id', $receivers)->whereRaw('( deleted is NULL || (deleted = 0))')->get()->map(function($user){
            return $user->id;
        })->toArray();

        return collect($users)->map(function($item) use ($customer_id) {
            return $customer_id . '#' . $item;
        })->toArray();

    }

}