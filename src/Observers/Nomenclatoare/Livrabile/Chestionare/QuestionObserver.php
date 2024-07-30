<?php

namespace MyDpo\Observers\Nomenclatoare\Livrabile\Chestionare;

use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\Question as Record;

class QuestionObserver {

    public function created(Record $record): void 
    {
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