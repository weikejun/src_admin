<?php
require_once("BaseTestCase.class.php");
class StockCommentTest extends BaseTestCase {
    // ...
    public function setUp(){
        DB::delete("delete from stock_comment;");
    }
    public function testAdd()
    {
        // Arrange
        $c = new StockCommentController();
        $_POST['comment']="comment";
        $_POST['stock_id']=1;
        $ret=$c->addAction();
        $this->assertEquals($ret[1]['errno'],0);
        $this->assertNotNull($ret[1]['rst']);
    }
    public function testDel(){
        $comment=new StockComment();
        $id=$comment->insert([
            'stock_id'=>1,
            'comment'=>"comment",
            'user_id'=>User::getCurrentUser()->mId,
        ]);
        $_POST['id']=$id;
        $c = new StockCommentController();
        $ret=$c->delAction();
        $this->assertEquals($ret[1]['errno'],0);
    }
    public function testReply(){
        $comment=new DBTable('stock_comment');
        $id=$comment->insert([
            'stock_id'=>1,
            'comment'=>"comment",
            'user_id'=>User::getCurrentUser()->mId,
        ]);
        $c = new StockCommentController();
        $_POST['id']=$id;
        $_POST['comment']='reply comment';
        $ret=$c->replyAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertNotNull($ret[1]['rst']['id']);
    }
    
    public function testList(){
        $comment=new DBTable('stock_comment');
        for($i=0;$i<20;$i++){
            $id=$comment->insert([
                'stock_id'=>1,
                'comment'=>"comment$i",
                'user_id'=>User::getCurrentUser()->mId,
            ]);
        }
        $_GET['stock_id']=1;
        $c = new StockCommentController();
        $ret=$c->listAction();
        $this->assertEquals($ret[1]['errno'],0);
        $this->assertEquals(count($ret[1]['rst']['comments']),10);
    }

    // ...
}
