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
			
			if($customer)
			{
				// se declanseaza un eveniment notificare

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

}