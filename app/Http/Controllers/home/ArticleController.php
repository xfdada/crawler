<?php

namespace App\Http\Controllers\home;

use App\Http\Model\Index;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ArticleController extends Controller
{
    //首页默认
    public function Index(){
        //显示域名提交表单
        $data = DB::table('article')->orderBy('created_at','desc')->paginate(10);
        return view('article',compact('data'));
    }

    //查看详情页
    public function show($id){
        $data = DB::table('article')->find($id);
        return view('detail',compact('data'));
    }

    //更新
    public function update($id){
        $input = Input::all();
        $article = [
            'title'=>$input['title'],
            'origin'=>$input['origin'],
            'description'=>$input['describe'],
            'preview'=>$input['preview'],
            'content'=>$input['content']
        ];
        $res = DB::table('article')->where('id',$id)->update($article);
        if($res){
            return $this->ToJson(1,'','编辑成功');
        }
        return $this->ToJson(5,'','编辑失败');
    }


    public function edit($id){
        $host ="http://".$_SERVER['HTTP_HOST'].'/';
        $data = DB::table('article')->find($id);
        return view('toedit',compact('data','host'));
    }

}
