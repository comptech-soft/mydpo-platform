<?php

namespace MyDpo\Traits;

use Carbon\Carbon;

trait DaysDifference { 


    /**
     * Diferenta de azi pana la date_to
     * > 0 ==> OK
     * = 0 ==> expira azi
     * < 0 ==> a expirat
     */
    public function getDaysDifferenceAttribute() {   
        $now = Carbon::now();

        if( ! $this->date_to )
        {
            return [
                'now' => $now->format('Y-m-d'),
                'date_to' => $this->date_to,
                'days' => NULL,
                'hours' => NULL,
                'color' => NULL,
                'human' => NULL,
            ];
        }
        
        $expire = Carbon::createFromFormat('Y-m-d', $this->date_to);
        
        $daysDiff = $expire->diffInDays($now, false);
        $hoursDiff = $expire->diffInHours($now, false);

        $color = 'green';
        if($daysDiff > 0)
        {
            $color = 'red';
        }
        else
        {
            if($daysDiff == 0)
            {
                $color = 'orange';
                if($hoursDiff > 0)
                {
                    $color = 'red';
                }
            }
        }

        return [
            'now' => $now->format('Y-m-d'),
            'date_to' => $this->date_to,
            'days' => $daysDiff,
            'hours' => $hoursDiff,
            'color' => $color,
            'human' => $expire->diffForHumans($now),
        ];
    }

}