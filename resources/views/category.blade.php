@extends('master')

@section('title', '书籍类别')

@section('content')
<div class="weui-cells__title">选择书籍类别</div>

<div class="weui-cells">

    <div class="weui-cell weui-cell_select">
        <div class="weui-cell__bd weui-cell_primary">
            <select class="weui-select" name="select1">
                @foreach($categorys as $categorys)
                    <option value="{{$categorys->id}}">{{$categorys->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<div class="weui-cells" id="category_childenselect">
    <a class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__bd">
            <p>cell standard</p>
        </div>
    </a>
</div>
@endsection

@section('my-js')
    <script>
        _getCategory();

        $('.weui-select').change(function(){
            _getCategory();
        });
        function _getCategory(){
            var parent_id = $('.weui-select option:selected').val();
            $.ajax({
                url: '/service/category/parent_id/'+parent_id,
                type: 'GET',
                dataType: 'json',
                cache:false,
                success:function(data){
                    if(data==null){
                        error_message('服务器请求失败！');
                        return;
                    }
                    if(data.status!=0){
                        error_message(data.message);
                        return;
                    }
                    $('#category_childenselect').html('');
                    for (var i = 0; i <data.categorys.length ; i++) {
                        var next = '/product/category_id/'+data.categorys[i].id;
                        var node =     '<a class="weui-cell weui-cell_access" href="'+next+'">'+
                            '<div class="weui-cell__bd">'+
                            '<p>'+data.categorys[i].name+'</p>'+
                        '</div>'+
                        '</a> ';

                        $('#category_childenselect').append(node);
                    }

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