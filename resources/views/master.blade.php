<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/weui.css">
    <link rel="stylesheet" href="/css/book.css">
</head>
<body>
<div class="bk_title_bar">
    <img class="bk_back" src="/images/back.jpg" alt="" onclick="history.go(-1);">
    <p class="bk_title_contetn">注册</p>
    <img class="bk_menu" src="/images/menu.jpg" alt="" id="global_menu" onclick="onMenuClick()">
</div>
<div class="page">
    @yield('content')
</div>

<div class="bk_toptips"><span></span></div>
<!--BEGIN actionSheet-->
<div>
    <div class="weui-mask" id="iosMask" style="display: none"></div>
    <div class="weui-actionsheet" id="iosActionsheet">
        <div class="weui-actionsheet__title">
            <p class="weui-actionsheet__title-text">这是一个标题，可以为一行或者两行。</p>
        </div>
        <div class="weui-actionsheet__menu">
            <div class="weui-actionsheet__cell">用户中心</div>
            <div class="weui-actionsheet__cell">客服中心</div>
            <div class="weui-actionsheet__cell">示例菜单</div>
            <div class="weui-actionsheet__cell">示例菜单</div>
        </div>
        <div class="weui-actionsheet__action">
            <div class="weui-actionsheet__cell" id="iosActionsheetCancel">取消</div>
        </div>
    </div>
</div>

</body>
{{--<script src="/js/weui.js"></script>--}}
<script src="/js/jquery-3.3.1.min.js"></script>
<script>
    $('.bk_title_contetn').html(document.title);
    // function hideActionSheet(weuiActionsheet, mask){
    //     weuiActionsheet.removeClass('weui_actionsheet_toggle');
    //     mask.removeClass('weui_fade_toggle');
    //     weuiActionsheet.om('transitionend', function(){
    //         mask.hide();
    //     }).on('webitTransitionEnd', function(){
    //         mask.hide();
    //     });
    // }
    // ios
    $(function(){
        var $iosActionsheet = $('#iosActionsheet');
        var $iosMask = $('#iosMask');

        function hideActionSheet() {
            $iosActionsheet.removeClass('weui-actionsheet_toggle');
            $iosMask.fadeOut(200);
        }

        $iosMask.on('click', hideActionSheet);
        $('#iosActionsheetCancel').on('click', hideActionSheet);
        $("#global_menu").on("click", function(){
            $iosActionsheet.addClass('weui-actionsheet_toggle');
            $iosMask.fadeIn(200);
        });
    });

    function onMenuClick(){
        var mask = $('#mask');
        var weuiActionsheet = $('#weui_actionsheet');
        weuiActionsheet.addClass('weui_actionsheet_toggle');
        mask.show().addClass('weui_fade_toggle').click(function(){
            hideActionSheet(weuiActionsheet, mask);
        });
        $('#actionsheet_cancel').click(function(){
            hideActionSheet(weuiActionsheet, mask);
        });
    }

    function onMenuItemClick(index){
        var mask = $('#mask');
        var weuiActionsheet = $('#weui_actionsheet');
        hideActionSheet(weuiActionsheet, mask);
        if(index == 1){

        }else  if(index == 2){

        }else if(index == 3){

        }else{
            $('.bk_toptips').show();
            $('.bk_toptips span').html("敬请期待!");
            setTimeout(function(){$('.bk_toptips').hide();}, 2000);
        }
    }

    function error_message(message=''){
        $('.bk_toptips').show();
        $('.bk_toptips span').html(message);
        setTimeout(function(){$('.bk_toptips').hide();}, 2500);
    }
</script>
@yield('my-js')
</html>