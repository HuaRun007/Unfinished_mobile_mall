@extends('master')

@section('title', '购物车')

@section('content')
    <div class="page bk_content" style="top: 0;">
        <div class="weui-cells weui-cells_checkbox">
            @foreach($cart_items as $cart_item)
                <label class="weui-cell weui-check__label" for="{{$cart_item->product->id}}">
                    <div class="weui-cell__hd" style="width: 23px;">
                        <input type="checkbox" class="weui-check" name="cart_item" id="{{$cart_item->product->id}}" checked="checked">
                        <i class="weui-icon-checked"></i>
                    </div>
                <div class="weui-cell__hd weui-cell_primary" style="width: 100%;">
                    <div style="position: relative;">
                        <img class="bk_preview" src="{{$cart_item->product->preview}}"  alt="">
                        <div style="position: absolute;left: 120px;right: 0; top: 5px;">
                            <p>{{$cart_item->product->name}}</p>
                            <p class="bk_time" style="margin-top: 15px;">数量：<span class="bk_summary">{{$cart_item->count}}</span></p>
                            <p class="bk_time">总计：<span>￥{{$cart_item->product->price * $cart_item->count}}</span></p>
                        </div>
                    </div>
                </div>
                </label>
            @endforeach
        </div>
    </div>

    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <button class="weui-btn weui-btn_primary" onclick="_OnCharge()">结算</button>
        </div>
        <div class="bk_half_area">
            <button class="weui-btn weui-btn_plain-default" onclick="_OnDelete()">删除</button>
        </div>
    </div>
@endsection

@section('my-js')
    <script>
        function _OnDelete(){
            var product_id_arr = [];
            $('input:checked[name=cart_item]').each(function (index, val) {
                if($(this).attr('checked')== 'checked'){
                    product_id_arr.push($(this).attr('id'));
                }
            });

            if(product_id_arr.length == 0){
                error_message('请选择要删除的书籍');
                return;
            }

            $.ajax({
                url: '/service/cart/delete',
                type: 'GET',
                dataType: 'json',
                data:{product_ids:product_id_arr+''},
                cache:false,
                success:function(data){
                    if(data==null){
                        error_message('服务器请求失败！');
                        return;
                    }
                    if(data.status!=0){
                        error_message(data.message);
                        return;
                    }else {
                        error_message(data.message);
                    }
                    location.reload();
                },
                error:function(xhr, status, message){
                    console.log(xhr);
                    console.log(status);
                    console.log(message);
                },

            });
        }
    </script>
@endsection