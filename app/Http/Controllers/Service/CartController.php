<?php

namespace App\Http\Controllers\Service;

use App\Models\M3Request;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public  function addCart(Request $request, $product_id){

        $bk_cart = $request->cookie( 'bk_cart');
        $bk_cart_arr = ($bk_cart != null ?  explode(",", $bk_cart) : array() );

        $count = 1;
        foreach ($bk_cart_arr as &$value){
            $index = strpos($value, ':');
            if(substr($value,0, $index) == $product_id){
                $count = (int)substr($value, $index+1) +1;
                $value = $product_id .':'.$count;
                break;
            }
        }
 
        if ($count == 1){
            array_push($bk_cart_arr, $product_id.':'.$count);
        }

        $m3_request = new M3Request();
        $m3_request->status = 0;
        $m3_request->message = '添加成功';
        return response($m3_request->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));
    }

    public function deleteCart(Request $request){
        $m3_request = new M3Request();

        $product_ids = $request->input('product_ids', '');

        if($product_ids == ''){
            $m3_request->status = 1;
            $m3_request->message = '书籍ID为空';

            return $m3_request->toJson();
        }
        $product_id_arr = explode(',', $product_ids);

        $bk_cart = $request->cookie('bk_cart','');

        $bk_cart_arr = $bk_cart != null ? explode(',', $bk_cart) : array();

        foreach ($bk_cart_arr as $key => $value){
            $index = strpos($value, ':');
            $product_id = substr($value,0,$index);
            if(in_array($product_id, $product_id_arr)){
                array_splice($bk_cart_arr,$key , 1);
                continue;
            }
        }

        $m3_request->status = 0;
        $m3_request->message = '删除成功';

        return response($m3_request->toJson())->withCookie('bk_cart', implode(',',$bk_cart_arr));

    }
}
