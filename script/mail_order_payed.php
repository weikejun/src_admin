<?php

$finder = new Order();
$orders = $finder->addWhere('status', 'payed')->orderBy('create_time', 'ASC')->find();
$content = "<table border='1'><tr><td>订单ID</td><td>订单状态</td><td>下单时间</td><td>直播ID</td><td>挑款师</td><td>编辑</td><td>买手</td><td>收货人</td><td>联系电话</td><td>收货地址</td><td>邮政编码</td></tr>";
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
        $objCache['buyer'][$order->mLiveId] = $buyer;
    }
    $content .= '<tr>' 
        .'<td><a href="http://api.taoshij.com:8000/admin/order?__filter=id%3D' . $order->mId .'">' . $order->mId . '</a></td>'
        .'<td>已支付全款</td>'
        .'<td>' . date('Y-m-d H:i:s', $order->mCreateTime) . '</td>'
        .'<td><a href="http://api.taoshij.com:8000/admin/live?__filter=id%3D' . $order->mLiveId .'">' . $order->mLiveId . '</a></td>'
        .'<td>' . $live->mSelector . '</td>'
        .'<td>' . $live->mEditor . '</td>'
        .'<td>' . $buyer->mName . '</td>'
        .'<td>' . $order->mName . '</td>'
        .'<td>' . $order->mCellphone . '</td>'
        .'<td>' . $order->mProvince . ',' . $order->mCity . ',' . $order->mAddr . '</td>'
        .'<td>' . $order->mPostcode . '</td>'
        .'</tr>';
}
$content = $content . "</table>";

EMail::send([
    'title' => '[report]买手发货提醒',
    'content' => $content,
    'to' => 'op.buyer@aimeizhuyi.com'
]);
