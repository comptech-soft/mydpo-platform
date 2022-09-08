<?php
 
 namespace MyDpo\Scopes;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
 
class CursScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $builder->whereRaw("((`cursuri`.`deleted` IS NULL) OR (`cursuri`.`deleted` = 0))");
    }

}