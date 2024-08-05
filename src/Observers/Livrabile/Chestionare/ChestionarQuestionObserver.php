<?php

namespace MyDpo\Observers\Livrabile\Chestionare;

use MyDpo\Models\Livrabile\Chestionare\ChestionarQuestion as Record;

class ChestionarQuestionObserver {

    public function created(Record $record): void 
    {
        $record->chestionar->syncQuestionCount();
    }

    public function updated(Record $record): void 
    {  
        $record->chestionar->syncQuestionCount();
    }

    public function deleted(Record $record): void 
    {   
        $record->chestionar->syncQuestionCount();
    }

    public function restored(Record $record): void 
    {
    }

    public function forceDeleted(Record $record): void 
    {
    }
}