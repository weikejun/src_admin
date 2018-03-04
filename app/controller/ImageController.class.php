<?php

class ImageController extends BaseController{
    public function indexAction(){
        $file=$this->_REQUEST('file',null);
        if(!$file){
            return ['text:error'];
        }
        $webroot=ROOT_PATH."/webroot/";
        if(!file_exists($webroot.$file)){
            return ['text:error2'];
        }
        $width=intval($this->_REQUEST("width"));
        if(!in_array($width,[360,720,1440])){
            return ['text:error3'];
        }
        $new_path=ROOT_PATH."/webroot/cache/$width/".$file;

        if(!file_exists($new_path)){
            @mkdir(dirname($new_path),0777,true);
            $size=getimagesize($webroot.$file);
            if(isset($size['mime'])){
                $type=preg_replace("/^[^\/]*\//","",$size['mime']);
            }else{
                $type=null;
            }
            if($size[0]&&$width<=$size[0]){
                ImageMagick::resize($webroot.$file,$new_path,$width,$type);
            }else{
                //原图很小，不必要再缩小了
                copy($webroot.$file,$new_path);
            }
        }
        header("Content-Type:".mime_content_type($new_path));
        readfile($new_path);
        return ["text:"];
        //return ["redirect:/cache/$width/$file"];
    }
}

