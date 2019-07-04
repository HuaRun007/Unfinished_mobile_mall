<?php
/**
 * Created by PhpStorm.
 * User: HuaRun
 * Date: 2018/10/24
 * Time: 16:15
 */

namespace App\Models;


class M3Request
{
    public $status;
    public $message;

    public function toJson(){
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

}