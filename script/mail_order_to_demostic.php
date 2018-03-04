<?php

$finder = new Order();
$orders = $finder->addWhere('status', 'to_demostic')->addWhere('update_time', time()-86400*15, '<')->orderBy('create_time', 'ASC')->find();
$content = "<div style='font-size:16px;color:red;'>本邮件发送于：".date('Y-m-d H:i:s')."，订单状态可能已发生变化，联系用户前请查看最新订单状态。<a href='http://api.taoshij.com:8000/admin/order?__filter=status%3Dto_demostic&__order=asc'>查看全部待支付订单》</a></div><table border='1'><tr><td>订单ID</td><td>订单状态</td><td>更新时间</td><td>下单时间</td><td>直播ID</td><td>直播名</td><td>挑款师</td><td>编辑</td><td>买手</td><td>商品ID</td><td>商品名</td><td>收货人</td><td>联系电话</td><td>收货地址</td><td>邮政编码</td></tr>";
$objCache = [];
$live = null;
$buyer = null;
foreach($orders as $order) {
    if(isset($objCache['live'][$order->mLiveId])) {
        $live = $objCache['live'][$order->mLiveId];
    } else {
        $live = new Live;
        $live = $live->addWhere('id', $order->mLiveId)->select();
        $objCache['live'][$order->mLiveId] = $live;
    }
    if(isset($objCache['buyer'][$order->mBuyerId])) {
        $buyer = $objCache['buyer'][$order->mBuyerId];
    } else {
        $buyer = new Buyer;
        $buyer = $buyer->addWhere('id', $order->mBuyerId)->select();
        $objCache['buyer'][$order->mBuyerId] = $buyer;
    }
    if(isset($objCache['stock'][$order->mStockId])) {
        $stock = $objCache['stock'][$order->mStockId];
    } else {
        $stock = new Stock;
        $stock = $stock->addWhere('id', $order->mStockId)->select();
        $objCache['stock'][$order->mStockId] = $stock;
    }
    $content .= '<tr>' 
        .'<td><a href="http://api.taoshij.com:8000/admin/order?__filter=id%3D' . $order->mId .'">' . $order->mId . '</a></td>'
        .'<td>商品海外发出</td>'
        .'<td>' . date('Y-m-d H:i:s', $order->mUpdateTime) . '</td>'
        .'<td>' . date('Y-m-d H:i:s', $order->mCreateTime) . '</td>'
        .'<td><a href="http://api.taoshij.com:8000/admin/live?__filter=id%3D' . $order->mLiveId .'">' . $order->mLiveId . '</a></td>'
        .'<td>' . $live->mName . '</td>'
        .'<td>' . $live->mSelector . '</td>'
        .'<td>' . $live->mEditor . '</td>'
        .'<td>' . $buyer->mName . '</td>'
        .'<td>' . $stock->mId . '</td>'
        .'<td>' . $stock->mName . '</td>'
        .'<td>' . $order->mName . '</td>'
        .'<td>' . $order->mCellphone . '</td>'
        .'<td>' . $order->mProvince . ',' . $order->mCity . ',' . $order->mAddr . '</td>'
        .'<td>' . $order->mPostcode . '</td>'
        .'</tr>';
}
$content = $content . "</table>";

EMail::send([
    'title' => '[report]商品海外发出超时提醒',
    'content' => $content,
    'to' => ['zhangping@aimeizhuyi.com','xuanli@aimeizhuyi.com','lengqiying@aimeizhuyi.com','weikejun@aimeizhuyi.com','chendandan@aimeizhuyi.com','huangying@aimeizhuyi.com']
]);
