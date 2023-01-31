<?php

namespace MyDpo\Models;

class Knolyx {

    /**
     * #1. PUT {{baseURL}}/public/api/v1/user/provision
     */
    public static function CreateUser($user) {
        
		$kUserProvision =  \Http::withHeaders([
			'X-Project-Id' => config('knolyx.project_id'),
			'X-Api-Key' => config('knolyx.app_key')
		])
		->put(
			config('knolyx.endpoint') . 'user/provision',
			[
				'firstName' => $user->first_name, 
				'lastName' => $user->last_name,
				'email' => $user->email,
				'disableWelcomeEmail' => TRUE,
			]
		)
		->json();

		if( array_key_exists('id', $kUserProvision))
		{
			$user->k_id = $kUserProvision['id'];
			$user->save();    
		}

		$user->refresh();

        return $user;
    }

    /**
     * #2. GET {{baseURL}}/public/api/v1/business-rule/course/{{courseId}}
     */
    public static function GetCourseRole($course, $user) {
        $response =  \Http::withHeaders([
            'X-Project-Id' => config('knolyx.project_id'),
            'X-Api-Key' => config('knolyx.app_key')
        ])
        ->get(config('knolyx.endpoint') . 'business-rule/course/' . $course->k_id)
        ->json();


		$startDateTime = \Carbon\Carbon::now()->format('Y-m-d') . ' 00:00:00';
		$endDateTime = \Carbon\Carbon::now()->addMonth()->format('Y-m-d') . ' 23:59:59';

		\Log::info('Bum bum....' . $startDateTime . ' - ' . $endDateTime);
		
        if(count($response) == 0)
		{
			
			$response[] = [
				'name' => "All",
				'type' => "PRIVATE",
				'startDateTime' => "2022-07-21 21:00:00",
				'endDateTime' => "2022-11-29 22:00:00",
				'restrictions' => [
					'minimumTime' => false,
					'browseOrder' => "anyOrder",
					'minimumTimeValue' => "1 hours",
				],
				'associations' => [
					"USER" => []
				 ],
				'action' => "STUDENTS"
			];
		}
		else
		{
			$response[0] = [
				'name' => "All",
				'type' => "PRIVATE",
				'startDateTime' => "2022-07-21 21:00:00",
				'endDateTime' => "2022-11-29 22:00:00",
				'restrictions' => [
					'minimumTime' => false,
					'browseOrder' => "anyOrder",
					'minimumTimeValue' => "1 hours",
				],
				'associations' => [
					"USER" => []
				 ],
				'action' => "STUDENTS"
			];
		}
		
        $courseRole = $response[0];
		
        if( ! array_key_exists('USER', $courseRole['associations']) )
        {
            $courseRole['associations']['USER'] = [];
        }

        $courseRole['associations']['USER'][] = $user['k_id'];

        return [$courseRole];
    }

    /**
     * #3. POST {{baseURL}}/public/api/v1/business-rule/course/{{courseId}}
     */
    public static function SetCourseRole($course_id, $courseRole) {
		
        $response =  \Http::withHeaders([
            'X-Project-Id' => config('knolyx.project_id'),
            'X-Api-Key' => config('knolyx.app_key')
        ])
        ->post(
            config('knolyx.endpoint') . 'business-rule/course/' . $course_id,  
            $courseRole
        )->json();
		
        return $response;
    }

    /**
     * Atentia la paginare si size
     * Cum aduc toate?
     */
    public static function GetCourses($page, $size) {
        $response =  \Http::withHeaders([
            'X-Project-Id' => config('knolyx.project_id'),
            'X-Api-Key' => config('knolyx.app_key')
        ])->get(config('knolyx.endpoint') . 'course?pagination.page=' . $page . '&pagination.size=' . $size);

        return $response->json();
    }

    public static function getCourseImage($course_id) {
        $response =  \Http::withHeaders([
            'X-Project-Id' => config('knolyx.project_id'),
            'X-Api-Key' => config('knolyx.app_key')
        ])->get(config('knolyx.endpoint') . 'course/' . $course_id . '/image');
		
        $body = $response->getBody()->getContents();
				
        $base64 = base64_encode($body);
		
		if($base64 && (count($response->getHeader('Content-Type')) > 1))
		{				
			return [
				'mime' => $response->getHeader('Content-Type'),
				'base64' => $base64, 
			];
		}
        
		return NULL;
    }
}


