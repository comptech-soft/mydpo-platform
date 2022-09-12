<?php
 
namespace MyDpo\Scopes;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
 
class NotdeletedScope implements Scope {

    public function apply(Builder $builder, Model $model) {

        $instance = new $model();
        $table = $instance->getTable();

        $builder->whereRaw("((`" . $table . "`.`deleted` IS NULL) OR (`" . $table . "`.`deleted` = 0))");
    }

}