<?php

namespace App\Http\Model;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use QL\QueryList;

class Index extends Model
{
    //
    public function getArticle($url){
        //正则匹配主机地址
        preg_match('@^(?:https://)?([^/]+)@i', $url, $matches);
        $base_url = $matches[0];
        //截取参数部分
        $prams_url = strrchr($url,'/s?');
        //实例化请求类
        $client = new Client(['base_uri' =>$base_url]);
        $result = $client->request('get',$prams_url , [
                'headers' => [
                    "Proxy-Connection" => "keep-alive",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "no-cache",
                    "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36",
                    "Accept" => "image/x-xbitmap,image/jpeg,application/x-shockwave-flash,application/vnd.ms-excel,application/vnd.ms-powerpoint,application/msword,*/*",
                    "DNT" => "1",
                    "Accept-Encoding" => "gzip, deflate, sdch",
                    "Accept-Language" => "zh-CN,zh;q=0.8,en-US;q=0.7,en;q=0.6"
                ]
            ]
        );
        //获取内容主体部分
        $content = $result->getBody();
        //实例化并调用 QueryList类中的html方法
        $html = QueryList::html($content);
        //查找标题
        $data['title'] = $html->find('#activity-name')->text();
        //查找来源公众号
        $data['origin'] = $html->find('#js_name')->text();
        //查找内容部分 返回的为二维数组 第一个元素为标识类型 ，第二个为值
        $list = $html->find('#js_content>p')->map(function($item){
            if($item->find('')->text()){
                return ['p',$item->find('')->text()];
            }
            elseif($item->find('iframe')->attr('src')){
                return ['video',$item->find('iframe')->attr('src')];
            }
            elseif($item->find('img')->attr('data-src')){
//                return ['img',$item->find('img')->attr('data-src')];
                return ['img',$this->getWxImage($item->find('img')->attr('data-src'))];
            }
            return ['br'," "];
        });
        $data['preview'] = '';
        //取第一张图的缩略图
        foreach ($list as $v){
            if($data['preview']!=''){
                break;
            }
            if($v[0]=='img'&&$v[1]!=''){
                $file = $v[1];
                $save_dir = 'img/'.date("Ymd").'/';
                $file_name = md5(date('YmdHis').mt_rand(10000,99999)).strrchr($file,'.');
                $this->getcentreimg($file,$save_dir.$file_name,'200','200');
                $data['preview'] = $save_dir.$file_name;
            }
        }
        $data['content'] = json_encode($list);//不能用序列化存储到数据库中
//        插入数据库中
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $res = DB::table('article_cash')->insertGetId($data);
        if($res){
            return $res;
        }
        return false;

    }

    //微信文章中图片下载方法
    protected function getWxImage($url){
        //url地址为空时直接返回
        if($url ==''){
            return;
        }
        //判断存储目录是否存在，不是目录或者为空时创建默认目录
        $save_dir = 'img/'.date("Ymd").'/';
        if(!is_dir($save_dir)||$save_dir==''){
            $save_dir =  mkdir('img/'.date("Ymd")).'/';
        }
        //获取文件后缀名 采集图片
        $ext = strrchr($url,'=');
        if(!in_array($ext,['=png','=gif','=jpg','=jpeg'])){
            return;
        }
        //保存文件的文件名
        $file_name = md5(date('YmdHis').mt_rand(10000,99999)).str_replace('=','.',$ext);
        $curl = curl_init();//curl初始化
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $img = curl_exec ( $curl );
        curl_close($curl);
        $resource = @fopen($save_dir.$file_name, 'a');
        fwrite($resource, $img);
        fclose($resource);
        return $save_dir.$file_name;
    }

    /**
     * @param $src_ore  图片原路径
     * @param $src_thumb 生成缩略图地址
     * @param $width   缩略图宽
     * @param $height   缩略图高
     */
    public function getcentreimg($src_ore,$src_thumb,$width,$height){
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
    }
}
