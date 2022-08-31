<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolder;

class GetSummary extends Perform {

    public function Action() {

        $folders = \DB::select("
            SELECT
                COUNT(*) AS `count_folders`
            FROM 
                `customers-folders`
            WHERE 
                (`customers-folders`.`customer_id` = " . $this->input['customer_id'] . ") AND 
                ((`customers-folders`.`deleted` IS NULL) OR (`customers-folders`.`deleted` = 0))
        ");

        $files = \DB::select("
            SELECT
                `materiale-statuses`.`name`,
                COALESCE(`files-summary`.`count_files`, 0) AS `count_files`
            FROM `materiale-statuses`
            LEFT JOIN
                (
                    SELECT
                        COUNT(`customers-files`.id) AS count_files,
                        `customers-files`.`status`
                    FROM `customers-files`
                    WHERE 
                        (`customers-files`.customer_id = " . $this->input['customer_id'] . ") AND 
                        ( (`customers-files`.`deleted` IS NULL) OR (`customers-files`.`deleted` = 0) )
                    GROUP BY `customers-files`.`status`
                ) 
                AS `files-summary`
            ON `materiale-statuses`.`slug` = `files-summary`.`status`
        ");

        $this->payload = [

            'folders' => $folders[0],
            'files' => $files[0],
            
        ];
    
    }
}