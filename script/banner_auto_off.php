<?php

$finder = new IndexNew();

$banners = $finder->addWhere('valid', 'valid')->addWhere('channel',1)->find();
$liveIds = [];
$bMap = [];

for($i = 0; $i < count($banners); $i++) {
    if(strtolower($banners[$i]->mType) == 'stock') {
        $stock = new Stock();
        $stock = $stock->addWhere('id', $banners[$i]->mModelId)->select();
        if($stock) {
            $liveIds[] = $stock->mLiveId;
            $bMap[$stock->mLiveId][] = $banners[$i];
        }
    } elseif(strtolower($banners[$i]->mType) == 'live') {
        $liveIds[] = $banners[$i]->mModelId;
        $bMap[$banners[$i]->mModelId][] = $banners[$i];
    }
}

$lives = new Live();
$lives = $lives->addWhere("id", $liveIds, 'IN')->find();
array_map(function($live)use($bMap) {
    if($live->mEndTime < time() || $live->mStatus != 'verified') {
        array_map(function($map) {
            $map->mValid = 'invalid';
            $map->save();
        }, $bMap[$live->mId]);
    }
}, $lives);


