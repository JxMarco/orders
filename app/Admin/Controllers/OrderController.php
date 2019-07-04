<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\WorkProcedure;
use App\Models\OrderFollow;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

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

        // $grid->column('id', __('Id'));
        $grid->column('orderno', __('Orderno'));
        $grid->column('cstname', __('Cstname'));
        $grid->column('expressname', __('ExpressName'));
        $grid->column('orderdate', __('Orderdate'));
        $grid->column('receiver', __('Receiver'));
        $grid->column('procno', __('ProcNo'));
        $grid->column('status', __('Status'));
        $grid->column('isstart', __('IsStart'));
        $grid->column('isfinish', __('IsFinish'));
        $grid->column('isurgent', __('IsUrgent'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->append('<a href="orders/start/'.$actions->getKey().'" title="开工" ><i class="fa fa-paper-plane"></i></a>');
            // $actions->append('<a href="orders/stop/'.$actions->getKey().'" title="暂停" ><i class="fa fa-paper-plane"></i></a>');
            $actions->append('<a href="orders/urgent/'.$actions->getKey().'" title="加急" ><i class="fa fa-fighter-jet"></i></a>');
            $actions->append('<a href="orders/cancel/'.$actions->getKey().'" title="撤销" ><i class="fa fa-stop"></i></a>');
        });
 

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
        $show->field('expressname', __('ExpressName'));
        $show->field('orderdate', __('Orderdate'));
        $show->field('receiver', __('Receiver'));

        $show->field('address', __('Address'));
        $show->field('taobaodesc', __('TaobaoDesc'));
        $show->field('sellerdesc', __('SellerDesc'));

        $show->field('procno', __('ProcNo'));
        $show->field('status', __('Status'));
        $show->field('isstart', __('IsStart'));
        $show->field('isfinish', __('IsFinish'));
        $show->field('isurgent', __('IsUrgent'));

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
        $form->text('expressname', __('ExpressName'));
        $form->datetime('orderdate', __('Orderdate'))->default(date('Y-m-d H:i:s'));
        $form->text('receiver', __('Receiver'));
        $form->text('address', __('Address'));
        $form->text('taobaodesc', __('TaobaoDesc'));
        $form->text('sellerdesc', __('SellerDesc'));

        return $form;
    }
    
    protected function start($id) {
        $order = (new Order())->find($id);
        if ($order->isfinish){
            admin_toastr('工单已完工，不能再次开工', 'error');
            return back();
        }

        if ($order->status == 2){
            admin_toastr('工单已撤销，不能开工', 'error');
            return back();
        }
        
        $procno = $order->procno;
        if ((new Admin())->user()->procno == $procno) {
            if ($order->isstart) {
                $order->isstart = 1;
                $order->save();
                $msg = "工单开工！";
            } else {
                $workprocedure = WorkProcedure::where('procno', '>', $procno)->first();
                if ($workprocedure) {
                    $nextno = $workprocedure->procno;
                    $order->procno = $nextno;
                    $order->isstart = 0;
                    $order->save();
                } else {
                    $order->isfinish = 1;
                    $order->save();
                }
                $msg = "工单完工！";
            }

            // 生成工单记录
            $follow = new OrderFollow();
            $follow->userid = (new Admin)->user()->id;
            $follow->orderid = $id;
            $follow->typename = $msg;
            $follow->save();

            admin_toastr('工单开工', 'success');
        } else {
            admin_toastr('订单不在当前工序，不能开工', 'info');
        }
        
        return back();
    }

    public function urgent($id)
    {
        $order = (new Order())->find($id);
        if ($order->isurgent){
            admin_toastr('工单已加急，无需重复操作', 'info');
            return back();
        }
        
        // 生成工单记录
        $follow = new OrderFollow();
        $follow->userid = (new Admin)->user()->id;
        $follow->orderid = $id;
        $follow->typename = '工单加急';
        $follow->save();

        $order->isurgent = 1;
        $order->save();

        admin_toastr('工单加急完成', 'success');
        return back();
    }

    public function cancel($id)
    {
        $order = (new Order())->find($id);
        if ($order->isfinish){
            admin_toastr('工单已完工，不能撤销', 'error');
            return back();
        }

        if ($order->status == 2){
            admin_toastr('工单已撤销，无需重复撤销', 'error');
            return back();
        }

        $order->status = 2;
        $order->save();

        // 生成工单记录
        $follow = new OrderFollow();
        $follow->userid = (new Admin)->user()->id;
        $follow->orderid = $id;
        $follow->typename = '工单撤销';
        $follow->save();

        admin_toastr('工单撤销完成', 'success');
        return back();
    }
}
