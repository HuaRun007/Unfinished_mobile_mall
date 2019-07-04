@extends('master')

@section('title', '登录')

@section('content')

    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">账号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input"  type="text" name="username" placeholder="邮箱或手机号"/>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  name="password"  placeholder="请输入密码"/>
            </div>
        </div>


        <div class="weui-cell weui-cell_vcode">
            <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="validate_code" placeholder="请输入验证码"/>
            </div>
            <div class="weui-cell__ft">
                <img class="weui-vcode-img" src="/service/validatecode/create" />
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" onclick="onLoginClick()">登录</a>
    </div>
    <a href="/register" class="bk_bottom_tips bk_important">没有账号？去注册</a>
@endsection

@section('my-js')
    <script>
        $('.bk_title_contetn').html('登录');
        $('.weui-vcode-img').click(function(){
            $(this).attr('src', '/service/validatecode/create?random='+ Math.random());
        });

        function onLoginClick(){
            //账号
            var username = $('input[name=username]').val();
            if(username.length == 0){
                error_message('账号不能为空');
                return false;
            }
            if(username.indexOf('@')== -1){ //手机号
                if(username.length!=11 || username[0] != 1){
                    error_message('账号格式不正确');
                    return false;
                }

            } else{
                if(username.indexOf('.') == -1){
                    error_message('账号格式不对');
                    return false;
                }
            }

            var password = $('input[name=password]').val();
            if(password.length == 0){
                error_message('密码不能为空');
                return false;
            }
            if(password.length <6){
                error_message('密码不能小于6位');
                return false;
            }
            //验证码
            var validate_code = $('input[name=validate_code]').val();
            if(validate_code.length == 0){
                error_message('验证码不能为空');
                return false;
            }
            if(validate_code.length <4){
                error_message('验证码不能小于4位');
                return false;
            }

            //ajax登录请求
            $.ajax({
                url: '/service/login',
                dataType: 'json',
                type:'POST',
                data:{username:username,password:password,validate_code:validate_code,_token:"{{csrf_token()}}",},
                success:function(data){
                    if(data.status!=0){
                        error_message('登录失败，'+data.message);
                        return false;
                    }
                    error_message('登录成功！');
                    // window.location.href('/category');
                    window.location.href = "http://web.rebook.com/category";
                },
                error:function (xhr,status, message) {
                    console.log(xhr);
                    console.log(status);
                    console.log(message);
                }
            });

        }
    </script>
@endsection