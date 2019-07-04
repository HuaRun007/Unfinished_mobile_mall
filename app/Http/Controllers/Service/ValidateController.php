<?php

namespace App\Http\Controllers\Service;

use App\Entity\Member;
use App\Entity\TempEmail;
use App\Entity\TempPhone;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Request;
use App\Models\M3Request;
use App\Tool\SMS\SendTemplateSMS;
use App\Tool\ValidateCode\ValidateCode;
use Illuminate\Http\Request;

class ValidateController extends Controller
{
     public function create(Request $request)
    {
        $validateCode = new ValidateCode();
        $code_img = $validateCode->doimg();
        $request->session()->put('validate_code', $validateCode->getCode());
        return $code_img;
    }

    public function getCode(Request $request){
         return $request->get('validate_code');
    }

    public function sendSMS(Request $request){
         $phone = $request->input('phone'); //获取号码
         $M3Request = new M3Request();
         if($phone == ''){
            $M3Request->status=1;
            $M3Request->message = '手机号不能为空!';
            return $M3Request->toJson();
         }

        $sendTemplateSMS = new SendTemplateSMS();
        $charset = '1234567890';//随机因子
        $_len = strlen($charset)-1;
        $code = '';
        for ($i=0;$i<6;$i++) {
            $code .= $charset[mt_rand(0,$_len)];
        }

        $request = $sendTemplateSMS->SendTemplateSMS($phone, array($code, 10),1); //请求短信接口 接受返回的状态对象

        if ($request->status !=0){
            $M3Request->status=5;
            $M3Request->message = '发送失败!';
            return $M3Request->toJson();
        }

        $tempphone = new TempPhone();
        $tempphone->phone = $phone;
        $tempphone->code = $code;
        $tempphone->deadline = date('Y-m-d H:i:s', time()+10*60);
        $tempphone->save(); //把验证信息保存到临时的手机验证码表中

        $M3Request->status = 0;
        $M3Request->message = '发送成功!';
        return $M3Request->toJson();
    }

    public function validateEmail(Request $request){
        $member_id = $request->input('member_id', '');
        $code = $request->input('code', '');

        $tempEmail = TempEmail::where('member_id', $member_id)->first();
        if($tempEmail == null){
            return '验证异常！';
        }

        if($tempEmail->code == $code){
            if(time() > strtotime($tempEmail->deadline)){
                return '该链接已失效！';

            }
            $member = Member::find($member_id);
            $member->active = 1;
            $member->save();

            return redirect('/login');
        } else{
            return '连接已失效！';
        }

    }

}
