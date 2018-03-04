<?php
class Buyer extends Base_Buyer{
    public static function getAllStatus(){
        return array(
            ['notapply',"未申请"],
            ['apply',"申请中"],
            ['reject',"申请驳回"],
            ['be',"申请通过"],
            ['disable',"封号"],
        );
    }

    const PAGE_NUM = 10;

    public static function getCurrentBuyer(){
        global $IS_DEBUG;
        $buyer=new self();
        if($IS_DEBUG&&php_sapi_name()=='cli'){
            return $buyer->addWhere("name","wp")->select();
        }
        if(!isset($_SESSION['buyer'])){
            return false;
        }
        $buyerData=$_SESSION['buyer'];
        
        $buyer->clear()->setData($buyerData);
        return $buyer;
        //return $user->addWhere("id",$id)->select();
    
    }    
    public static function getBuyerInfo($buyerID){
        $buyer=new self();
        $buyer=$buyer->addWhere('id',$buyerID)->select();
        $data=$buyer->getData();
        if(isset($data['desc'])){
            $data['desc'] = (!empty($data['desc']))?json_decode($data['desc'],true):null;
            $data['desc'] = self::buyerDescSort($data['desc']);
        }
        $data['level'] = self::level($data['level']);
        $data = self::filterFiled($data);
        return $data;
    }

    /**
     * 等级转换函数
     * @param $level
     * @return array
     */
    public static function level($level){
        $level = $level + 1;
        if($level <= 5 && $level >= 0){
            return array('type' => 1, 'num' => $level);
        }else if($level >= 6 && $level <= 25){
            return array('type' => 2, 'num' => floor(($level /5)));
        }else if($level >= 26 && $level <= 125){
            return array('type'=> 3, 'num'=>floor(($level/25)));
        }else if($level >= 126 and $level <= 625){
            return array('type'=> 4, 'num'=>floor(($level/125)));
        }
    }

    /**
     * 等级升级
     */
    public static function levelup($buyerID){
        $buyer=new self();
        $buyer=$buyer->addWhere('id',$buyerID)->select();
        $buyer->mLevel+=1;
        $buyer->save();
    }

    public function idPics(){
        $idPics=json_decode($this->mIdPics,true);
        if(!$idPics){
            $idPics=[];
        }
        return $idPics;
    }
    public function favorBrands(){
        $favorBrands=json_decode($this->mFavorBrands,true);
        if(!$favorBrands){
            $favorBrands=[];
        }
        return $favorBrands;
    }

    /**
     * 推荐买手列表的默认排序
     * @param $country
     * @param $category
     * @param int $pageId
     * @return array
     */
    public function getBuyerList($country, $category, $pageId = 0, $count = 10){
        $buyer = new self();
        if($count >= 20 || $count <= 1 || empty($count)){
            $count = self::PAGE_NUM;
        }
        if(!empty($country) && $country != "全球"){
            $buyer->addWhere('country',$country);
        }
        $offset = $pageId * $count;
        //todo:目前类目不做为查询条件进行使用
        $buyerList = $buyer->addWhere('status','be')->orderBy('update_time','desc')->limit($offset,$count)->find();
        $buyerList = array_map(function($buyer){
            $data = $buyer->getData();
            if(isset($data['desc'])){
                $data['desc'] = (!empty($data['desc']))?json_decode($data['desc'],true):array();
                $data['desc'] = self::buyerDescSort($data['desc']);
            }
            $data = self::filterFiled($data);
            return $data;
        },$buyerList);
        return $buyerList;
    }

    /**
     * 批量获取买手信息
     * @param $buyerIdList
     * @return array
     */
    public function getListByIdList($buyerIdList){
        if(count($buyerIdList) <= 0){
            return array();
        }else{
            $buyerList = $this->addWhere('id',$buyerIdList,'in')->findMap('id');
            $sortBuyerList = array();
            foreach($buyerIdList as $buyerId){
                $sortBuyerList []= $buyerList[$buyerId];
            }
            $buyerList = array_filter($sortBuyerList);
            $buyerList = array_map(function($buyer){
                $data=$buyer->getData();
                if(isset($data['desc'])){
                    $data['desc'] = (!empty($data['desc']))?json_decode($data['desc'],true):array();
                    $data['desc'] = self::buyerDescSort($data['desc']);
                }
                $data['level'] = self::level($data['level']);
                $data = self::filterFiled($data);
                return $data;
            },$buyerList);
            return $buyerList;
        }
    }

    //取买手注册人数
    static public function getBuyerNum() {
        $buyer = new self();
        $buyer = $buyer->addWhere('status','be')->count();
        return $buyer;
    }

    /**
     * 获取买手印象tag列表
     */
    public static function getBuyerTagList(){
        return array(
            '发货快',
            '态度好',
            '回复及时',
            '说明很清楚',
            '很热情',
            '人美心善',
        );
    }

    /**
     * 过滤敏感信息
     * @param $data
     */
    public static function filterFiled($data){
        $filterField = array("password");
        foreach($filterField as $field){
            unset($data[$field]);
        }
        return $data;
    }

    /**
     * 买手tag的排序方法
     * @param $desc
     * @return array
     */
    public static function buyerDescSort($desc){
        if(!isset($desc)){
            return null;
        }
        $numArray = array();
        foreach($desc as $key => $value){
            $numArray []= $value;
        }
        rsort($numArray);
        $ret = array();
        foreach(array_unique($numArray) as $num){
            foreach($desc as $key => $value){
                if($num == $value){
                    $ret[$key] = $num;
                }
            }
        }
        return $ret;
    }
}
