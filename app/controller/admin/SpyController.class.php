<?php
class SpyController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        WinRequest::mergeModel(array(
            'controllerText' => "特工模式",
        ));
    }

    public function indexAction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $admin = new Model_Admin;
            $admin->addWhere('id', $_POST['admin_id']);
            $admin->select();
            if ($admin->mId) {
                $_SESSION['admin'] = $admin->getData();
                return ["redirect: /admin/index"];
            }
        }
        $this->form=new Form([
            ['name'=>'admin_id','label'=>'变身用户','type'=>'choosemodel','model'=>'Model_Admin'],
        ]);
        return ["admin/spy/index.html",['form'=>$this->form]];
    }
}


