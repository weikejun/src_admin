<?php
$finder = new Order();
$orders = $finder->addWhere('status', 'wait_pay')->orderBy('create_time', 'ASC')->find();
$content = "<div style='font-size:16px;color:red;'>本邮件发送于：".date('Y-m-d H:i:s')."，订单当前可能已支付，联系用户前请查看最新订单状态。<a href='http://api.taoshij.com:8000/admin/order?__filter=status%3Dwait_pay&__order=asc'>查看全部待支付订单》</a></div><table border='1'><tr><td>订单ID</td><td>订单状态</td><td>下单时间</td><td>直播ID</td><td>联系电话</td><td>收货信息</td></tr>";
foreach($orders as $order) {
    $sDesc = '';
    switch($order->mStatus) {
    case 'wait_pay':
        $sDesc = '备货完毕，等待支付全款';
        break;
    }
    $content .= '<tr>' 
        .'<td><a href="http://api.taoshij.com:8000/admin/order?__filter=id%3D' . $order->mId .'">' . $order->mId . '</a></td>'
        .'<td>' . $sDesc . '</td>'
        .'<td>' . date('Y-m-d H:i:s', $order->mCreateTime) . '</td>'
        .'<td><a href="http://api.taoshij.com:8000/admin/live?__filter=id%3D' . $order->mLiveId .'">' . $order->mLiveId . '</a></td>'
        //.'<td>' . $order->mCellphone . '</td>'
        .'<td><a href="http://api.taoshij.com:8000/admin/order?__filter=id%3D' . $order->mId .'">查看</a></td>'
        .'<td>收货人：' . $order->mName . '<br>收货地址：' . $order->mProvince . ',' . $order->mCity . ',' . $order->mAddr . '<br>邮编：' . $order->mPostcode . '</td>'
        .'</tr>';
}
$content = $content . "</table>";

EMail::send([
    'title' => '[report]用户付款提醒',
    'content' => $content,
    'to' => 'op.user@aimeizhuyi.com'
]);
