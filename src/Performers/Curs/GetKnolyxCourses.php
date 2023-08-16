<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\SysConfig;
use MyDpo\Models\Livrabile\ELearning\Knolyx;
use MyDpo\Models\Curs;

class GetKnolyxCourses extends Perform {

    public function GetCourses($page, $size) {
		
        $result = Knolyx::GetCourses($page, $size);

        if( count($result['list']) > 0 )
        {
            Curs::saveCoursesFromKnolyx($result['list']);
            $this->GetCourses($page + 1, $size);
        }
        
    }

    public function Action() {
        $cursuriStart = Curs::whereNotNull('k_id')->get()->map( function($item) {
			return $item->id;
		})->toArray();
		
		
		
		$this->GetCourses(0, 20);
		
		$cursuriEnd = Curs::whereNotNull('k_id')->get()->map( function($item) {
			return $item->id;
		})->toArray();
		
		$diff = collect($cursuriStart)->diff(collect($cursuriEnd))->toArray();
		
		foreach($diff as $i => $id)
		{
			$curs = Curs::find($id);
			$curs->name = $curs->name . ' (' . $curs->k_id . ')';
			$curs->k_id = $curs->id * 1000000 + $curs->k_id;
			$curs->deleted = 1;
			
			$curs->save();
		}
		
    }

}