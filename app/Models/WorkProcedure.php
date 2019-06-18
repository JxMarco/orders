<?php

namespace App\Models;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Database\Eloquent\Model;

class WorkProcedure extends Model
{
    use ModelTree, AdminBuilder;
    
    protected $table = 'workprocedure';
    
    public static function grid($callback)
    {
        return new Grid(new static, $callback);
    }
    
    public static function form($callback)
    {
        return new Form(new static, $callback);
    }
    
    public static function getWorkProcedure() {
        return WorkProcedure::all();
    }

}
