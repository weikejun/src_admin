<?php
$stock=new Stock();
$stocks=$stock->addWhere('live_id',"1")->orderBy("id")->find();
$imgs=[];
foreach($stocks as $stock){
    $_imgs=json_decode($stock->mImgs,true);
    if(is_array($_imgs)){
        $imgs=array_merge($imgs,$_imgs);
    }
}
var_dump($imgs);
