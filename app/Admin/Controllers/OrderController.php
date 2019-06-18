<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->column('id', __('Id'));
        $grid->column('orderno', __('Orderno'));
        $grid->column('cstname', __('Cstname'));
        $grid->column('orderdate', __('Orderdate'));
        $grid->column('duedate', __('Duedate'));
        $grid->column('procno', __('Procno'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('orderno', __('Orderno'));
        $show->field('cstname', __('Cstname'));
        $show->field('orderdate', __('Orderdate'));
        $show->field('duedate', __('Duedate'));
        $show->field('procno', __('Procno'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('orderno', __('Orderno'));
        $form->text('cstname', __('Cstname'));
        $form->datetime('orderdate', __('Orderdate'))->default(date('Y-m-d H:i:s'));
        $form->datetime('duedate', __('Duedate'))->default(date('Y-m-d H:i:s'));
        $form->text('procno', __('Procno'));

        return $form;
    }
}
