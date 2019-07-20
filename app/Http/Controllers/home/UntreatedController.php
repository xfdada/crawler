<?php

namespace App\Http\Controllers\home;

use App\Http\Model\Index;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class UntreatedController extends Controller
{
    //首页默认
    public function Index(){
        //显示域名提交表单
        $data = DB::table('article_cash')->orderBy('created_at','desc')->paginate(10);
        return view('fail',compact('data'));
    }
}
