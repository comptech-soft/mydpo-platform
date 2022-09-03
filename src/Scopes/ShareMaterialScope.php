<?php
 
 namespace MyDpo\Scopes;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
 
class ShareMaterialScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $builder->whereRaw("((`share-materiale`.`deleted` IS NULL) OR (`share-materiale`.`deleted` = 0))");
    }

}