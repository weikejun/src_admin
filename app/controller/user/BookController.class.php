<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-25
 * Time: 上午10:13
 */
class BookController extends AppBaseController{
    public function __construct(){
    }

    /**
     * 图墙，瀑布流
     */
    public function wallAction(){
        $categoryId = $this->_GET('category_id');
        $lastId = $this->_GET('lastId',0);
        $count = $this->_GET('count',20);
        $categoryName = "";
        $curLastId = $lastId; //用来标识最后一个id

        foreach((new StockBook())->category() as $category){
            if($category['categoryId'] == $categoryId){
                $categoryName = $category['category'];
            }
        }

        $list = (new StockBook())->getBookListByCategoryId($categoryId,$lastId,$count);
        $stockIdList = array_map(function($pic){
            return $pic['stock_id'];
        },$list);

        foreach($list as $pic){
            if($pic['id'] >= $curLastId){
                $curLastId = $pic['id'];
            }
        }

        $stockList = (new Stock())->genStockDetailOfStockList($stockIdList);
        //喜欢列表
        $stockFavorList = (new Favor())->likeStockList($stockIdList);
        $stockList = array_map(function($stock)use($stockFavorList){
            $stock['followed'] = empty($stockFavorList[$stock['id']])?false:true;
            return $stock;
        },$stockList);
        $flowInfo = $this->_FLOW($lastId,$curLastId, count($list),$count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'categoryName' =>$categoryName,
            'stockList' => array_values($stockList),
            'flowInfo'=>$flowInfo,
        ])];
    }
}
