<?php
/*
//user
$user = new User();
$sql = "select user_id, count(*) as cc from `order` where status='success' group by user_id";
$res = DB::query($sql);
foreach( $res as $user ) {
    $user_id = $user['user_id'];
    $u = new User();
    $user = $u->addWhere('id', $user_id)->select();
    $user = $user->getData();
    if( false === strpos($user['avatar_url'], 'public_upload') ) continue;
    uploadImageByUrl($user['avatar_url']);
}

//indexNew
$index = new IndexNew();
$indexs = $index->addWhere('id', 0, '>')->orderBy('id', 'DESC')->find();
foreach( $indexs as $index ) {
    $index = $index->getData();
    $imgs = json_decode($index['imgs'], true);
    foreach( $imgs as $img ) {
        uploadImageByUrl($img);
    }
}

//buyer
$buyer = new Buyer();
$buyers = $buyer->addWhere('id', 0, '>')->orderBy('id', 'DESC')->find();
foreach( $buyers as $buyer ) {
    $buyer = $buyer->getData();
    uploadImageByUrl($buyer['head']);
    uploadImageByUrl($buyer['background_pic']);
    uploadImageByUrl($buyer['background_pic_small']);
}

//live
$live = new Live();
$lives = $live->addWhere('id', 0, '>')->orderBy('id', 'DESC')->find();
foreach( $lives as $live ) {
    $live = $live->getData();
    $imgs = json_decode($live['imgs'], true);
    foreach( $imgs as $img ) {
        //var_dump($img); exit;
        uploadImageByUrl($img);
    }
}

//stock
$id = 50000000;
while(true) {
    $stock = new Stock();
    $stocks = $stock->addWhere('id', $id, '<')->orderBy('id', 'DESC')->limit(10)->find();
    if( empty($stocks) ) break;

    logger('id: '.$id);
    foreach( $stocks as $stock ) {
        $stock = $stock->getData();
        $imgs = json_decode($stock['imgs'], true);
        $id = $stock['id'];
        foreach( $imgs as $img ) {
            uploadImageByUrl($img);
        }
    }
    //exit;
}
*/

$stock = new Stock();
$stocks = $stock->addWhere('live_id', 1008)->orderBy('id', 'DESC')->limit(100)->find();
foreach( $stocks as $stock ) {
    $stock = $stock->getData();
    $imgs = json_decode($stock['imgs'], true);
    foreach( $imgs as $img ) {
        uploadImageByUrl($img);
    }
}

function uploadImageByUrl($url) {
    $data = explode('/', $url);
    $file = array_pop($data);
    $data = explode('.', $file);
    $ext = $data[1];
    $md5 = $data[0];
    
    $base_dir = PUBLIC_IMAGE_BASE;
    $reldir=$md5[0]."/".$md5[1]."/".$md5[2]."/";

    try{
        if( !is_dir($base_dir.$reldir) ) {
            $m = mkdir($base_dir.$reldir,0777,true);
        }
        $file_path=$base_dir.$reldir.$md5.".$ext";
        if( file_exists($file_path) ) {
            return;
        }

        //var_dump( $file_path ); 
        //var_dump(file_exists($file_path), is_dir($base_dir.$reldir)); exit;
        $content = Login::get_url("http://img.taoshij.com".$url);
        file_put_contents($file_path, $content);
    } catch(Exception $e) {
        throw new ModelAndViewException("unknown error",1,"json:",AppUtils::returnValue(['msg'=>'upload unknown error'],99999));
    }

    logger($url);
    usleep(300000);
    return;
}

function logger($str) {
    file_put_contents("/data/downloadUrl.log", date('Y-m-d H:i:s').":".$str."\n", FILE_APPEND);
}
