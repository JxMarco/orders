<?php

namespace App\Models;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    public static function grid($callback)
    {
        return new Grid(new static, $callback);
    }
    
    public static function form($callback)
    {
        return new Form(new static, $callback);
    }
}
