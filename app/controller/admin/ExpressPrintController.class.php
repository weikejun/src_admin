<?php
class ExpressPrintController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new ExpressPrint();
        $this->model->setAutoClear(false);
        $this->model->orderBy('id', 'desc');

        $this->form=new Form(array(
            array('name'=>'storage_ids','label'=>'库存ID','type'=>'text','default'=>$this->fieldsDefault['storage_ids'],'required'=>true,'readonly'=>true),
            //array('name'=>'num','label'=>'打印数量','type'=>"text",'default'=>count($usersMap),'required'=>true,'readonly'=>true),
            array('name'=>'print_time','label'=>'打印时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
        ));
        $this->list_display=[
            ['label'=>'流水号','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'订单ID','field'=>function($model){
                return $model->mStorageIds;
            }],
            ['label'=>'发货时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mPrintTime);
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'storage_ids','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'打印时间','paramName'=>'create_time']),
        );

        $this->single_actions_default=[
            'edit' => false,
            'delete' => false,
        ];

        $this->hide_action_new = true;
    }

    public function create() {
        $storageIds = $this->_POST('storage_ids');
        $storageIds = explode(',', $storageIds);
        $storages = new Storage;
        $storages = $storages->addWhere('id', $storageIds, 'in')->setCols(['id', 'order_id'])->findMap('order_id');
        $orderIds = array_keys($storages);
        $model = new ExpressPrint;
        $model->mStorageIds = implode(',', $orderIds);
        $model->mPrintTime = time();
        $model->save();
        $this->display('redirect: /admin/order/print?vendor=_sender&ids='.implode(',', $orderIds));
    }
}
