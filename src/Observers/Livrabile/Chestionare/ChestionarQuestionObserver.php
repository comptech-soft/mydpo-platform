<?php

namespace MyDpo\Observers\Livrabile\Chestionare;

use MyDpo\Models\Livrabile\Chestionare\ChestionarQuestio as Record;

class ChestionarQuestionObserver {

    public function created(Record $record): void 
    {
        dd($record->toArray());
    }

    public function updated(Record $record): void 
    {  
    }

    public function deleted(Record $record): void 
    {   
    }

    public function restored(Record $record): void 
    {
    }

    public function forceDeleted(Record $record): void 
    {
    }
}