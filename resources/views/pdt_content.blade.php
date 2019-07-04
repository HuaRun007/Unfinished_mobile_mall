@extends('master')

@section('title',$product->name)

@section('content')
    <link rel="stylesheet" type="text/css" href="/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/entypo.css">
    <div class="a-1_2">
        <div class='o-sliderContainer hasShadow' id="pbSliderWrap">
            <div class='o-slider' id='pbSlider'>
                @foreach($pdt_images as $pdt_images)
                <div class="o-slider--item" data-image="{{$pdt_images->image_path}}"></div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="weui-cells__title">
        <span class="bk_title">{{$product->name}}</span>
        <span class="bk_price" style="float: right">￥{{$product->price}}</span>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <p class="bk_summary">{{$product->summary}}</p>
        </div>
    </div>

    <div class="weui-cells__title">详情介绍</div>
    <div class="weui-cells">
        <div class="weui-cell bk_detail_content">
            @if($pdt_content != null)
                {!! $pdt_content->content !!}
            @else

            @endif
        </div>
    </div>

    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <button class="weui-btn weui-btn_primary" onclick="_addCart({{$product->id}})">加入购物车</button>
        </div>
        <div class="bk_half_area">
            <button class="weui-btn weui-btn_plain-default" onclick="_toCart()">结算（<span class="bk_price" id="cat_num">{{$count}}</span>）</button>
        </div>
    </div>
@endsection

@section('my-js')
    <script src='/js/hammer.min.js'></script>
    <script src='/js/slider.js'></script>
    <script>
        $('#pbSlider').pbTouchSlider({
            slider_Wrap: '#pbSliderWrap',
            slider_Threshold: 50,
            slider_Speed:400,
            slider_Ease:'linear',
            slider_Drag : false,
            slider_Arrows: {
                enabled : false
            },
            slider_Breakpoints: {
                default: {
                    height: 500
                },
                tablet: {
                    height: 250,
                    media: 1024
                },
                smartphone: {
                    height: 200,
                    media: 768
                }
            }
        });

        function _addCart($product_id){
            $.ajax({
                url: '/service/cart/add/'+$product_id,
                type: 'GET',
                dataType: 'JSON',
                success:function(data){
                    if(data.status!=0){
                        error_message('添加失败，'+data.message);
                        return false;
                    }
                    if(data.status==0){
                        error_message(data.message);
                        var num = $('#cat_num').html();
                        if(num == '') num = 0;
                        $('#cat_num').html(Number(num)+1);
                        return false;
                    }
                },
                error:function(xhr,status,message){
                    console.log(xhr);
                    console.log(status);
                    console.log(message);
                }
            });
        }

        function _toCart(){
            console.log('212312');
            window.location.href = '/cart';
        }
    </script>
@endsection()