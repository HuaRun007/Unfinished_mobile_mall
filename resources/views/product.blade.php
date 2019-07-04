@extends('master')

@section('title','书籍类别列表')

@section('content')
    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__hd">图文组合列表</div>
            <div class="weui-panel__bd">
                @foreach($products as $products)
                <a href="/product/product_id/{{$products->id}}" class="weui-media-box weui-media-box_appmsg">
                    <div class="bk_box_hd">
                        <img class="weui-media-box__thumb" src="{{$products->preview}}" alt="">
                    </div>
                    <div class="weui-media-box__bd weui-cell_primary">
                        <div style="margin-bottom: 10px;">
                            <span class="bk_title">{{$products->name}}</span>
                            <span class="bk_price" style="float: right;">￥{{$products->price}}</span>
                        </div>
                        <p class="bk_summary">{{$products->summary}}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('my-js')

@endsection()