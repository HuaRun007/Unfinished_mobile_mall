<?php

namespace App\Http\Controllers\Service;

use App\Entity\Member;
use App\Entity\TempEmail;
use App\Entity\TempPhone;
use App\Models\M3Email;
use App\Models\M3Request;
use App\Tool\UUID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    public function register(Request $request){
         $email = $request->input('email');
         $phone = $request->input('phone');
         $password = $request->input('password');
         $password_again = $request->input('password_again');
         $phone_code = $request->input('phone_code');
         $validate_code = $request->input('validate_code');
        $m3request = new M3Request();
        if($phone=='' && $email==''){
            $m3request->status = 1;
            $m3request->message = '手机号或邮箱号不能为空！';
            return $m3request->toJson();
        }
        if($password=='' && $password_again==''){
            $m3request->status = 2;
            $m3request->message = '密码不能为空！';
            return $m3request->toJson();
        }
        if(strlen($password)<6){
            $m3request->status = 3;
            $m3request->message = '密码不能小于6位！';
            return $m3request->toJson();
        }
        if($password != $password_again){
            $m3request->status = 4;
            $m3request->message = '密码不一致！';
            return $m3request->toJson();
        }

        //手机注册
        if($phone!==''){
            if($phone_code!=='' ||strlen($phone_code)<6){
                $m3request->status = 5;
                $m3request->message = '手机验证码为6位！';
                return $m3request->toJson();
            }
            $tempPhone = TempPhone::where('phone', $phone);
            if($tempPhone->code == $phone_code){
                if(time()>strtotime($tempPhone->deadline)){
                    $m3request->status = 7;
                    $m3request->message = '手机验证码不正确！';
                    return $m3request->toJson();
                }
                $member = new Member();
                $member->phone = $phone;
                $member->password = substr(md5($password),3,20);
                $member->save();

                $m3request->status = 0;
                $m3request->message = '注册成功！';
                return $m3request->toJson();

            }else{
                $m3request->status = 7;
                $m3request->message = '手机验证码不正确！';
                return $m3request->toJson();
            }

            //邮箱注册
        }else {
            if($validate_code == '' || strlen($validate_code)<4){
                $m3request->status = 6;
                $m3request->message = '验证码为4位！';
                var_dump($validate_code);
                return $m3request->toJson();
            }

            $validate_code_session = $request->session()->get('validate_code', '');

            if($validate_code == $validate_code_session){
                $member = new Member();
                $member->email = $email;
                $member->password = substr(md5($password),3,20);
                $member->save();

                $user_id = $member->getQueueableId();
                //发送邮件
                $uuid = UUID::create();
                $m3_email = new M3Email();
                $m3_email->to = $email;
                $m3_email->cc = '15212119227@163.com';
                $m3_email->subject = '华润测试验证';
                $m3_email->content = '请于24小时点击该链接完成验证。http://web.rebook.com/service/validate_email'
                                    .'?member_id='.$user_id
                                    .'&code='.$uuid;

                $tempEmail = new TempEmail();
                $tempEmail->member_id = $user_id;
                $tempEmail->code = $uuid;
                $tempEmail->deadline = date('Y-m-d H:i:s', time()+24*60*60);
                $tempEmail->save();

                Mail::send('email_register',['m3_email'=>$m3_email], function ($m) use ($m3_email){
//                    $m->from('123123', 'asdfsfd');

                    $m ->to($m3_email->to, '尊敬的用户')
                        ->cc($m3_email->cc)
                        ->subject($m3_email->subject);
                });

                $m3request->status = 0;
                $m3request->message = '注册成功！';
                return $m3request->toJson();
            }else{
                $m3request->status = 8;
                $m3request->message = '验证码不正确！';
                return $m3request->toJson();
            }
        }


    }

    public  function login(Request $request){
        //获取前端数据
        $username = $request->get('username', '');
        $password = $request->get('password', '');
        $validate_code = $request->get('validate_code', '');

        $m3_request = new M3Request();
        //校验



        //判断
        $validate_code_session = $request->session()->get('validate_code', '');

        if($validate_code != $validate_code_session){
            $m3_request->status = 1;
            $m3_request->message = '验证码错误';
            return $m3_request->toJson();
        }

        $member = null;
        $is_emialuser = false; //是否是邮箱注册用户
        if(strpos($username, '@') == true ){
            $member = Member::where('email', $username)->first();
            $is_emialuser = true;
        } else{
            $member = Member::where('phone', $username)->first();
        }

        if($member == null){
            $m3_request->status = 2;
            $m3_request->message = '该用户不存在';
            return $m3_request->toJson();
        } else {
            //判断密码是否正确
            if(substr(md5($password), 3, 20) != $member->password){
                $m3_request->status = 3;
                $m3_request->message = '密码不正确';
                return $m3_request->toJson();
            }
        }

        if($is_emialuser && $member->active == 0){
            $m3_request->status = 4;
            $m3_request->message = '账户没激活请去邮箱激活账号' ;
            return $m3_request->toJson();
        }
        $request->session()->put('member', $member);

        $m3_request->status = 0;
        $m3_request->message = '登录成功';
        return $m3_request->toJson();
    }
}
