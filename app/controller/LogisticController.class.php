<?php
/**
 * Created by PhpStorm.
 * User: boshen
 * Date: 14-12-9
 * Time: 下午11:48
 */


class LogisticController extends AppBaseController{

    public function indexAction(){
    }

    //物流状态更新回调接口
    //by boshen@20141210
    public function logisticUpdateCallbackAction(){
        //set header
        header("Content-Type:text/html;charset=utf-8");

        $logger =  PLogger::get();

        //开始处理接受到的数据
        $param=$this->_POST('param');
        $logger->INFO("[callback]:".$param);
        $param = json_decode($param, true);
        if(!isset($param['lastResult']) || empty($param['lastResult'])) {
            $this->errorAction();
        } else {
            try{
                $data = $param['lastResult'];
                $logistic_no = $data['nu'];
                $logistic_provider = $data['com'];
                if(empty($logistic_no) || empty($logistic_provider) || '200'!=$data['status']) {
                    $this->errorAction();
                }

                $now_time = time();
                $logistic_tracking = new LogisticTracking();

                //开始保存物流信息（可能是多条记录）
                $data = array_reverse($data['data']);
                foreach($data as $k=>$record) {
                    $logistic_tracking->clear();
                    $record['ftime'] = strtotime($record['ftime']);
                    $row = $logistic_tracking->addWhere('logistic_no', $logistic_no)->addWhere('logistic_provider', $logistic_provider)->addWhere('ftime', $record['ftime'])->limit(1)->select();
                    if(false!==$row) continue;

                    $row = array();
                    $row['logistic_no'] = $logistic_no;
                    $row['logistic_provider'] = $logistic_provider;
                    $row['context'] = trim($record['context']);
                    $row['ftime'] = $record['ftime'];
                    $row['create_time'] = $now_time;
                    $tracking_id = $logistic_tracking->insert($row);
                    //var_dump($tracking_id);

                    $info = "save logistic trackings success! id: {$tracking_id}, logistic_no: {$logistic_no}, logistic_provider: {$logistic_provider}";
                    $logger->INFO($info);
                }
                $this->successAction();
            } catch(Exception $e){
                $this->errorAction();
            }
        }
    }

    //保存失败，返回失败信息，30分钟以后会重推
    protected function errorAction() {
        echo  '{"result":"false","returnCode":"500","message":"失败"}'; exit;
    }

    //要返回成功（格式与订阅时指定的格式一致），不返回成功就代表失败，没有这个30分钟以后会重推
    protected function successAction() {
        echo  '{"result":"true","returnCode":"200","message":"成功"}'; exit;
    }

}
