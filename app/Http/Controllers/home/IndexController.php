<?php

/**
 * create by xfdada
 * time 2019-07-20
 * 内容爬取
 */
namespace App\Http\Controllers\home;

use App\Http\Model\Index;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //首页默认
    public function Index(){
        //显示域名提交表单
        return view('index');
    }
    //数据处理和数据入库
    public function store(){
        $url = Input::get('url');
        $index = new Index();
        $res = $index->getArticle($url);
        if($res){
            return $this->ToJson(1,['id'=>$res],'爬取成功');
        }
        return $this->ToJson(5,'','爬取失败');
    }
    //展示添加页面
    public function create(){
    }
    //查看详情页
    public function show($id){
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
        $res = DB::table('article')->insert($article);
        if($res){
            DB::table('article_cash')->delete($id);
            return $this->ToJson(1,'','编辑成功');
        }
        return $this->ToJson(5,'','编辑失败');
    }
    //删除
    public function destroy($id){
        $res = DB::table('article_cash')->delete($id);
        if ($res){
            return $this->ToJson(1,'','删除成功');
        }
        return $this->ToJson(5,'','删除失败');
    }
    //展示编辑页面
    public function edit($id){
        $host ="http://".$_SERVER['HTTP_HOST'].'/';
        $data = DB::table('article_cash')->find($id);
        $data->content = json_decode($data->content,true);
        return view('edit',compact('data','host'));
    }

    //缩略图创建
    protected function getcentreimg($src_ore,$src_thumb,$width,$height){
        $img = getimagesize($src_ore);//载入图片的函数   得到图片的信息
        switch($img[2]){//判断图片的类型
            case 1:
                $im=@imagecreatefromgif($src_ore);//载入图片，创建新图片
                break;
            case 2:
                $im=@imagecreatefromjpeg($src_ore);
                break;
            case 3:
                $im=@imagecreatefrompng($src_ore);
                break;
        }
        $width_y=$img[0];
        $height_y=$img[1];
        if($width_y>$height_y){//如果宽大于高
            $width_y_y=$height_y;
            $height_y_y=$height_y;
            $jq_x=($width_y-$height_y)/2;
            $jq_y=0;
        }else if($width_y<$height_y){//如果宽小于高
            $height_y_y=$width_y;
            $width_y_y=$width_y;
            $jq_x=0;
            $jq_y=($height_y-$width_y)/2;
        }else if($width_y=$height_y){//如果宽小于高
            $width_y_y=$width_y;
            $height_y_y=$height_y;
            $jq_x=0;
            $jq_y=0;
        }
        $newim=imagecreatetruecolor($width,$height); //剪切图片第一步，建立新图像 x就是宽 ，y就是高//图片大小
        imagecopyresampled($newim,$im,0,0,$jq_x,$jq_y,$width,$height,$width_y_y,$height_y_y);//这个函数不失真
        imagejpeg($newim,$src_thumb);
        return true;
    }

    public function createImg(Request $request){
        $file = $request->file('file');
        $data = $this->Img_Upload($file);
//        dd($data);
        $save_dir = 'img/'.date("Ymd").'/';
        $file_name = md5(date('YmdHis').mt_rand(10000,99999)).'.png';
        $res = $this->getcentreimg($data,$save_dir.$file_name,'200','200');
        if($res){
            unlink($data);
            return $this->ToJson(1,$save_dir.$file_name,'上传成功');
        }
        return $this->ToJson(5,'','上传失败');
    }
    private function Img_Upload($file){//  单文件上传
        // 允许上传文件名的拓展名
        $allowed_extensions = ["png", "jpg", "gif","jpeg"];
        //$file->getClientOriginalExtension()    获取文件名的拓展名
        //下面方法是 有没有拓展名 并且拓展名不在数组里面  返回错误信息
        if($file->getClientOriginalExtension()&&!in_array($file->getClientOriginalExtension(),$allowed_extensions)){
            return false;
        }
        //创建目录
        if(!is_dir('img/'.date('Ymd'))){
            $path = mkdir('img/'.date('Ymd'));
        }
        $path = 'img/'.date('Ymd');

        $extension = $file->getClientOriginalExtension();//获取拓展名
        $fileName = md5(str_random(10) . time()) . '.' . $extension;//生成文件名
        $file->move($path, $fileName);//移动到指定目录下
        $url = $path.'/'.$fileName;
        return $url;
    }
}
