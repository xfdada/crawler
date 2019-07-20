<?php
namespace resources\helper;
class image{
    private $_width;
    private $_height;
    private $_type;
    //实例化时获取图片信息
    function __construct($file_url){
        $info = getimagesize($file_url);
        $this->_width = $info[0];
        $this->_height = $info[1];
        $type = $info['mime'];
        $type = explode('/',$type);
        $this->_type = $type[1];
    }
    //返回图片类型
    public function getImageType(){
        return $this->_type;
    }
    //返回图片宽度
    public function getImageWidth(){
        return $this->_width;
    }
    //返回图片高度
    public function getImageHeight(){
        return $this->_height;
    }

    /*
    **图片裁剪
    ** $tmp_image源文件
    ** $dst_w 裁剪后的图片的宽度
    ** $dst_h 裁剪后的图片的高度
    ** $x 在源图的$x处坐标开始裁剪
    ** $y 在源图的$y处坐标开始裁剪
    */
    public function crop($tmp_image,$dst_w,$dst_h,$x,$y,$path){
        switch($this->_type){
            case 'jpeg':
                $src = imagecreatefromjpeg($tmp_image);
                break;
            case 'gif':
                $src = imagecreatefromgif($tmp_image);
                break;
            case 'png':
                $src = imagecreatefrompng($tmp_image);
                break;
        }

        $dst = imagecreatetruecolor($dst_w,$dst_h);
        $color = imagecolorallocate($dst,255,255,255);
        imagecolortransparent($dst,$color);
        imagefill($dst,0,0,$color);
        $bool = imagecopyresampled($dst,$src, 0,0,$x,$y, $dst_w,$dst_h,$dst_w,$dst_h);
        switch($this->_type){
            case 'jpeg':
                imagejpeg($dst,$path,100);
                break;
            case 'gif':
                imagegif($dst,$path);
                break;
            case 'png':
                imagepng($dst,$path);
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);
        return $bool;
    }

    /*
    ** 等比例缩放图片
    ** $tmp_image源图
    ** $dst_w 缩放后的图片宽度
    ** $dst_h 缩放后的图片高度
    */
    public function reduce($tmp_image,$dst_w,$dst_h,$path){
        switch($this->_type){
            case 'jpeg':
                $src = imagecreatefromjpeg($tmp_image);
                break;
            case 'gif':
                $src = imagecreatefromgif($tmp_image);
                break;
            case 'png':
                $src = imagecreatefrompng($tmp_image);
                break;
        }
        $imagex = imagesx($src);
        $imagey = imagesy($src);
        $dst = imagecreatetruecolor($dst_w,$dst_h);
        $color = imagecolorallocate($dst,255,255,255);
        imagecolortransparent($dst,$color);
        imagefill($dst,0,0,$color);
        $bool = imagecopyresampled($dst,$src,0,0,0,0,$dst_w,$dst_h,$imagex,$imagey);
        switch($this->_type){
            case 'jpeg':
                imagejpeg($dst,$path,100);
                break;
            case 'gif':
                imagegif($dst,$path);
                break;
            case 'png':
                imagepng($dst,$path);
                break;
        }
        imagedestroy($src);
        imagedestroy($dst);
        return $bool;
    }
}