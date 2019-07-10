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
                $retVal = '1000';
                $retMsg = '登录成功！';
                $retData = $user;
            } else {
                $retVal = '1002';
                $retMsg = '用户名和密码不匹配，请重新输入！';
                $retData = [];
            }
            
        } else {
            $retVal = '1001';
            $retMsg = '用户名不存在，请确认！';
            $retData = [];
        }

        $arrRet = array("code"=>$retVal, "msg"=>$retMsg, "data"=>$retData);
        return json_encode($arrRet);
    }
    
    public function OrderDetail($orderno) {
        $order = Order::where('orderno', $orderno)->first();
        return json_encode($order);
    }
    
    public function ScanOrderNo($userid, $orderno, $procno) {
        $order = Order::where('orderno', $orderno)->first();
        if ($order) {
            if ($order->isfinish == 1) {
                $retVal = '9999';
                $retMsg = '工单已完成！';
                $arrRet = array("code"=>$retVal, "msg"=>$retMsg);
                return json_encode($arrRet);
            }
            if ($order->procno === $procno) {
                $procname = '';
                $workprocedure = WorkProcedure::where('procno', $procno)->first();
                if ($workprocedure) {
                    $procname = $workprocedure->procname;
                }
                
                if ($order->isstart == 0 ) {
                    $retVal = '2000';
                    $retMsg = '工单开工！';
                    $type = '工单开工';
                    $order->isstart = 1;
                    $order->save();
                } else {
                    $retVal = '2001';
                    $retMsg = '工单完工！';
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
                $retVal = '2003';
                $retMsg = '工单不在当前工序上，请确认工单单号！';
            }
        } else {
            $retVal = '2002';
            $retMsg = '工单不存在，请确认是否已导入！';
        }
    
        $arrRet = array("code"=>$retVal, "msg"=>$retMsg);
        return json_encode($arrRet);
    }

}
