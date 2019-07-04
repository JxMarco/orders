<?php

namespace App\Admin\Controllers;

use App\Models\WorkProcedure;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WorkProcedureController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '工序管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WorkProcedure);

        $grid->column('id', __('Id'));
        $grid->column('procno', __('ProcNo'));
        $grid->column('procname', __('ProcName'));
        $grid->column('order', __('Order'));
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
        $show = new Show(WorkProcedure::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('procno', __('ProcNo'));
        $show->field('procname', __('ProcName'));
        $show->field('order', __('Order'));
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
        $form = new Form(new WorkProcedure);

        $form->text('procno', __('ProcNo'));
        $form->text('procname', __('ProcName'));
        $form->number('order', __('Order'));

        return $form;
    }
}
