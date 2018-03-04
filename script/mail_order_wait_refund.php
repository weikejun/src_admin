<?php
$finder = new Order();
$orders = $finder->addWhere('status', ['wait_refund','timeout','returned'],'in')->orderBy('create_time', 'ASC')->find();
$content = "<table border='1'><tr><td>订单ID</td><td>订单状态</td><td>下单时间</td><td>直播ID</td><td>收货人ID</td><td>收货人</td><td>联系电话</td><td>收货地址</td><td>邮政编码</td></tr>";
foreach($orders as $order) {
    $sDesc = '';
    switch($order->mStatus) {
    case 'wait_refund':
        $sDesc = '备货失败，等待退款';
        break;
    case 'returned':
    case 'timeout':
        $sDesc = '未及时支付，订单取消';
        break;
    }
    $content .= '<tr>' 
        .'<td><a href="http://api.taoshij.com:8000/admin/order?__filter=id%3D' . $order->mId .'">' . $order->mId . '</a></td>'
        .'<td>' . $sDesc . '</td>'
        .'<td>' . date('Y-m-d H:i:s', $order->mCreateTime) . '</td>'
        .'<td><a href="http://api.taoshij.com:8000/admin/live?__filter=id%3D' . $order->mLiveId .'">' . $order->mLiveId . '</a></td>'
        .'<td>' . $order->mUserId . '</td>'
        .'<td>' . $order->mName . '</td>'
        .'<td>' . $order->mCellphone . '</td>'
        .'<td>' . $order->mProvince . ',' . $order->mCity . ',' . $order->mAddr . '</td>'
        .'<td>' . $order->mPostcode . '</td>'
        .'</tr>';
}
$content = $content . "</table>";

EMail::send([
    'title' => '[report]用户退款提醒',
    'content' => $content,
    'to' => 'op.user@aimeizhuyi.com'
]);
