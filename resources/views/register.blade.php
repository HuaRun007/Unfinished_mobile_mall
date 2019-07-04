@extends('master')
@section('title', '注册')

@section('content')
    <div class="weui-cells__title">注册方式</div>
    <div class="weui-cells weui-cells_radio">
        <label class="weui-cell weui-check__label" for="x11">
            <div class="weui-cell__bd">
                <p>手机号注册</p>
            </div>
            <div class="weui-cell__ft">
                <input type="radio" class="weui-check" name="register_type" id="x11"  value="1" checked/>
                <span class="weui-icon-checked"></span>
            </div>
        </label>
        <label class="weui-cell weui-check__label" for="x12">

            <div class="weui-cell__bd">
                <p>邮箱注册</p>
            </div>
            <div class="weui-cell__ft">
                <input type="radio" class="weui-check" name="register_type"  id="x12" value="2" />
                <span class="weui-icon-checked"></span>
            </div>
        </label>
    </div>
    <div class="weui-cells_title"></div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input"   type="text" pattern="[0-9]*"  name="phone" placeholder="请输入手机号"/>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  name="password_phone"  placeholder="不少于6位"/>
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"   name="password_phone_again" placeholder="确认密码"/>
            </div>
        </div>

        <div class="weui-cell weui-cell_vcode">
            <div class="weui-cell__hd">
                <label class="weui-label">手机验证码</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="phone_code" placeholder="请输入手机验证码"/>
            </div>
            <div class="weui-cell__ft">
                <button class="weui-vcode-btn">获取验证码</button>
            </div>
        </div>

    </div>
    {{--邮箱注册--}}
    <div class="weui-cells weui-cells_form" style="display: none">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">邮箱</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input"   type="text" name="email" placeholder="请输入邮箱"/>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password" name="password_email"   placeholder="不少于6位"/>
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  name="password_email_again"  placeholder="确认密码"/>
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


    <div class="weui-cells_title"></div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary"  onclick="onRegister();"  id="showTooltips">注册</a>
    </div>
    <a href="/login" class="bk_bottom_tips bk_important">已经有账号？去登录</a>
@endsection

@section('my-js')
    <script>
        $('.weui-vcode-img').click(function(){
            $(this).attr('src', '/service/validatecode/create?random'+ Math.random());
        });

        $("input:radio[name=register_type]").click(function(event){
            if($(this).attr('id')=='x11'){
                $(".weui-cells_form").eq(0).show();
                $(".weui-cells_form").eq(1).hide();
            }else if($(this).attr('id')=='x12'){
                $(".weui-cells_form").eq(0).hide();
                $(".weui-cells_form").eq(1).show();

            }
        });
    </script>
    <script>
        var enable = true;
        $(".weui-vcode-btn").click(function(event){
            var phone = $.trim($('input:text[name=phone]').val());
            if(phone == ''){
                error_message('手机号不能为空！');
                return false;
            }
            if(!isPoneAvailable(phone)){
                error_message('请输入正确的手机号！');
                return false;
            }
            if (enable==false){
                return;
            }
            enable = false;
            var num = 60;
            var interval = window.setInterval(function(){
                $('.weui-vcode-btn').addClass('bk_summary');
                $('.weui-vcode-btn').html(--num+'s 重新发送');
                if(num==0){
                    $('.weui-vcode-btn').removeClass('bk_summary');
                    window.clearInterval(interval);
                    $('.weui-vcode-btn').html('&nbsp;重新发送');
                    enable = true;
                }
                },1000);

            $.ajax({
                url: '/service/validate/sendSMS',
                dataType: 'json',
                cache:false,
                data: {phone: phone},
                success:function(data){
                    if(data==null){
                        error_message('发送失败！');
                        return;
                    }
                    if(data.status!=0){
                        error_message(data.message);
                        return;
                    }
                    error_message('发送成功！');
                },
                error:function(xhr, status, message){
                    console.log(xhr);
                    console.log(status);
                    console.log(message);
                },

            });


        });


        //验证手机号的正确性
        function isPoneAvailable(phone_number) {
            var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;

            if (!phoneReg.test(phone_number)) {
                return false;
            } else {
                return true;
            }
        }
        //验证邮箱正确性
        function isEmailAvailable(email_number){
            var mailReg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            if(!mailReg.test(email_number)){
                return false;
            } else {
                return true;
            }
        }

    </script>
    <script>

        function onRegister(){
            $('input:radio[name=register_type]').each(function(index, el){
                if($(this).is(':checked')==true){
                    var email = '';
                    var phone = '';
                    var password = '';
                    var password_again = '';
                    var phone_code = '';
                    var validate_code = '';

                    var id = $(this).attr('id');
                    var verification = false;
                    if(id == 'x11'){
                        phone          = $('input:text[name=phone]').val();
                        password       = $(':password[name=password_phone]').val();
                        password_again = $(':password[name=password_phone_again]').val();
                        phone_code     = $('input:text[name=phone_code]').val();
                        verification = verificationPhone(phone, password, password_again, phone_code);
                    }else if(id == 'x12'){
                        email          = $('input:text[name=email]').val();
                        password       = $(':password[name=password_email]').val();
                        password_again = $(':password[name=password_email_again]').val();
                        validate_code     = $('input:text[name=validate_code]').val();
                        verification = verificationEmial(email, password, password_again, validate_code);
                    }
                    if(!verification){
                        return false;
                    }
                    $.ajax({
                        url: '/service/register',
                        dataType: 'json',
                        type:'POST',
                        data:{phone:phone,email:email,password:password,password_again:password_again,
                            phone_code:phone_code,validate_code:validate_code,_token:"{{csrf_token()}}"},
                            success:function(data){
                                if(data.status!=0){
                                    error_message('注册失败，'+data.message);
                                    return false;
                                }
                                if(data.status==0){
                                    error_message('注册成功！');
                                    return false;
                                }
                            },
                            error:function (xhr,status, message) {
                                console.log(xhr);
                                console.log(status);
                                console.log(message);
                            }
                    });
                }
            });
        }
        function verificationPhone(phone, password, password_again, phone_code){
            if(phone == ''){
                error_message('手机号不为空！');
                return false;
            }
            if(!isPoneAvailable(phone)){
                error_message('请填写正确的手机！');
                return false;
            }
            if(password== ''){
                error_message('请输入密码！');
                return false;
            }
            if(password!==password_again){
                error_message('两次密码不一致!');
                return false;
            }
            if(phone_code==''){
                error_message('请填写手机验证码！');
                return false;
            }

            return true;
        }

        function verificationEmial(email, password, password_again, validate_code){
            if(email == ''){
                error_message('邮箱号并不能为空！');
                return false;
            }
            console.log(email);
            if(!isEmailAvailable(email)){
                error_message('请输入正确的邮箱格式！');
                return false;
            }
            if(password== ''){
                error_message('请输入密码！');
                return false;
            }
            if(password!==password_again){
                error_message('两次密码不一致!');
                return false;
            }
            if(validate_code==''){
                error_message('请填写验证码！');
                return false;
            }
            return true;

        }
    </script>
@endsection