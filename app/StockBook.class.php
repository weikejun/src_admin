<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-3
 * Time: 下午9:16
 */
class StockBook extends Base_Stock_Book{

    private static $ins = null;

    /**
     * 单例模式
     * @return null|StockBook
     */
    public static function getInstance(){
        if(empty(self::$ins)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 图墙上
     */
    const ON = 1;

    /**
     * 下图墙
     */
    const OFF = 0;

    public static function category(){
        return [
            [
                'category'=>"打折村",
                'url'=>"AMCustomerURL://wall?category_id=1&title=打折村",
                'img'=>"/flag/category/icon_home_1.png",
                'categoryId'=> 1,
                'categoryIconName'=>"discount"
            ],
            [
                'category'=>"美容",
                'url'=>"AMCustomerURL://wall?category_id=2&title=美容",
                'img'=>"/flag/category/icon_home_2.png",
                'categoryId'=> 2,
                'categoryIconName'=>"beauty",
            ],
            [
                'category'=>"服装",
                'url'=>"AMCustomerURL://wall?category_id=3&title=服装",
                'img'=>"/flag/category/icon_home_3.png",
                'categoryId'=> 3,
                'categoryIconName'=>"clothing",
            ],
            [
                'category'=>"鞋包",
                'url'=>"AMCustomerURL://wall?category_id=4&title=鞋包",
                'img'=>"/flag/category/icon_home_4.png",
                'categoryId'=> 4,
                'categoryIconName'=>"shoe-bag",
            ],
            [
                'category'=>"母婴",
                'url'=>"AMCustomerURL://wall?category_id=5&title=母婴",
                'img'=>"/flag/category/icon_home_5.png",
                'categoryId'=> 5,
                'categoryIconName'=>"infant",
            ],
            [
                'category'=>"珠宝配饰",
                'url'=>"AMCustomerURL://wall?category_id=6&title=珠宝配饰",
                'img'=>"/flag/category/icon_home_6.png",
                'categoryId'=> 6,
                'categoryIconName'=>"jewelry",
            ],
            [
                'category'=>"生活保健",
                'url'=>"AMCustomerURL://wall?category_id=7&title=生活保健",
                'img'=>"/flag/category/icon_home_7.png",
                'categoryId'=> 7,
                'categoryIconName'=>"life",
            ],
            [
                'category'=>"特色",
                'url'=>"AMCustomerURL://wall?category_id=8&title=特色",
                'img'=>"/flag/category/icon_home_8.png",
                'categoryId'=> 8,
                'categoryIconName'=>"feature",
            ]
        ];
    }

    /**
     * 根据分类id获取图墙列表
     * @param $categoryId
     * @param $lastId
     * @param $count
     * @return array
     */
    public function getBookListByCategoryId($categoryId, $lastId=0, $count= 20){
        if(empty($categoryId)){
            return array();
        }else{
            if(!$lastId){
                $lastId = 0;
            }
            $list = $this->addWhere('category_id',$categoryId)->addWhere('status',self::ON)->addWhere('id',$lastId,">")->limit(0,$count)->find();
            $list = array_map(function($book){
                return $book->getData();
            },$list);
            return $list;
        }
    }

    /**
     * 把商品从图墙上下架
     * @param $stockIdList
     * @return false
     */
    public function offWall($stockIdList){
        if(count($stockIdList) == 0) {
            return false;
        }else{
            return $this->addWhere('stock_id',$stockIdList,'in')->update(array(
                'status' => self::OFF,
            ));
        }
    }

    /**
     * 商品同步到图墙(商品下架)
     * @param $data
     * @param $action
     * @return bool | int
     */
    public function stockSyncBook($data,$action){
        if(empty($data['id'])){
            return false;
        }
        switch($action){
            case "update":
                $bookData = array(
                    'stock_id' => $data['id'],
                    'status' => $data['onshelf'],
                );
                //stockId为空，或者商品状态为上架状态，则不作处理
                if(empty($bookData['stock_id']) || $bookData['status'] == 1){
                    return false;
                }else{
                    return $this->offWall(array($bookData['stock_id']));
                }
                break;
            case "delete":
                $bookData = array(
                    'stock_id' => $data['id'],
                );
                if(empty($bookData['stock_id'])){
                    return false;
                }else{
                    return $this->offWall(array($bookData['stock_id']));
                }
                break;
        }
    }
}