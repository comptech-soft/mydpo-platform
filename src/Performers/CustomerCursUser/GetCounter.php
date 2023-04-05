<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;


class GetCounter extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        $sql = "
            SELECT 
                COUNT(*) count_all,
                SUM(IF(`status` = 'sended', 1, 0)) count_pending,
                SUM(IF(`status` = 'done', 1, 0)) count_done,
                SUM(IF(`status` = 'started', 1, 0)) count_started
            FROM `customers-cursuri-users`
            WHERE 
                (user_id = " . $this->input['user_id'] . ") AND
                (customer_id = " . $this->input['customer_id'] . ") AND
                ( (deleted = 0) OR (deleted IS NULL) )
            ";

        $result = \DB::select($sql);
        
        $this->payload = $result[0];
    
    }
}