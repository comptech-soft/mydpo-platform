<?php
 
 namespace MyDpo\Scopes;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
 
class FolderScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $builder->whereRaw("((`customers-folders`.`deleted` IS NULL) OR (`customers-folders`.`deleted` = 0))");
    }

}