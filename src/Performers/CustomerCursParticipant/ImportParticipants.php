<?php

namespace MyDpo\Performers\CustomerCursParticipant;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursFile;
use MyDpo\Models\Customer\ELearning\CustomerCurs;
use MyDpo\Imports\CustomerCursParticipant\Importer;

class ImportParticipants extends Perform {

    public function Action() {

        $importer = new Importer($this->input);

        \Excel::import($importer, $this->input['file']);

        CustomerCurs::syncUsersCounts($this->input['customer_id']);
    }

}