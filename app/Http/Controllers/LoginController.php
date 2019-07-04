<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Order;
use App\Models\WorkProcedure;
use App\Models\OrderFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function Login($username, $pass){
        $user = Login::where('username', $username)->first();
        if ($user) {
            if (Hash::check($pass, $user->password)) {
                $retval = '1000';
                $retmsg = '登录成功！';
                $data = $user;
            } else {
                $retval = '1002';
                $retmsg = '用户名和密码不匹配，请重新输入！';
                $data = [];
            }
            
        } else {
            $retval = '1001';
            $retmsg = '用户名不存在，请确认！';
            $data = [];
        }
        
        $ret = [$retval, $retmsg, $data];
        return json_encode($ret);
    }
    
    public function OrderDetail($orderno) {
        $order = Order::where('orderno', $orderno)->first();
        return json_encode($order);
    }
    
    public function ScanOrderNo($userid, $orderno, $procno) {
        $order = Order::where('orderno', $orderno)->first();
        if ($order) {
            if ($order->isfinish == 1) {
                $retval = '9999';
                $retmsg = '工单已完成！';
                $ret = [$retval, $retmsg];
                return json_encode($ret);
            }
            if ($order->procno === $procno) {
                $procname = '';
                $workprocedure = WorkProcedure::where('procno', $procno)->first();
                if ($workprocedure) {
                    $procname = $workprocedure->procname;
                }
                
                if ($order->isstart == 0 ) {
                    $retval = '2000';
                    $retmsg = '工单开工！';
                    $type = '工单开工';
                    $order->isstart = 1;
                    $order->save();
                } else {
                    $retval = '2001';
                    $retmsg = '工单完工！';
                    $type = '工单完工';
                    
                    // 获取下一个工序
                    $workprocedure = WorkProcedure::where('procno', '>', $procno)->first();
                    if ($workprocedure) {
                        $order->procno = $workprocedure->procno;
                        $order->isstart = 0;
                        $order->save();
                    } else {
                        $order->isfinish = 1;
                        $order->save();
                    }
                }
                
                
                
                // 生成开工流水记录
                $follow = new OrderFollow();
                $follow->userid = $userid;
                $follow->orderid = $order->id;
                $follow->typename = $procname . ' - ' . $type;
                $follow->save();
            } else {
                $retval = '2003';
                $retmsg = '工单不在当前工序上，请确认工单单号！';
            }
        } else {
            $retval = '2002';
            $retmsg = '工单不存在，请确认是否已导入！';
        }
    
        $ret = [$retval, $retmsg];
        return json_encode($ret);
    }
}
