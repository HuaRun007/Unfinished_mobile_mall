<?php

namespace App\Http\Controllers\Service;

use App\Entity\Category;
use App\Models\M3Request;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public  function getCategoryByParentId($parent_id){
        $categorys = Category::where('parent_id',$parent_id)->get();
        $m3_request = new M3Request();
        $m3_request->status = 0;
        $m3_request->message = '返回成功';
        $m3_request->categorys = $categorys;
        return $m3_request->toJson();
    }
}
