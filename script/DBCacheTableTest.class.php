<?php
require_once('BaseTestCase.class.php');
if(!class_exists("KvStorage")){
class KvStorage{
    private $map;
    public function get($key){
        return $this->map[$key];
    }
    public function set($key,$value){
        $this->map[$key]=$value;
    }
    public function mget($keys){
        $ret=[];
        foreach($keys as $key){
            $ret[]=$this->get($key);
        }
        return $ret;
    }
    public function mset($map){
        foreach($map as $k=>$v){
            $this->set($k,$v);
        }
    }
    public function del($keys){
        foreach($keys as $key){
            unset($this->map[$key]);
        }
    }
}
}
class DBCacheTableTest extends BaseTestCase{
    public function setUp(){
        $this->table=new DBCacheTable("live");
        $this->table->setKvStorage(new KvStorage());
        DB::beginTransaction();
    }
    public function tearDown(){
        DB::rollBack();
    }
    public function testFind(){
        $values=$this->table->limit(0,10)->find();
        $this->assertEquals(10,count($values));
        
        $value=$this->table->addWhere("id",1)->select();
        $this->assertEquals(1,$value['id']);
    }
    public function testFindFromCache(){
        $value=$this->table->addWhere("id",1)->select();
        $value=$this->table->addWhere("id",1)->addWhere("name","ive%",'like')->select();
        $this->assertFalse($value);
        $value=$this->table->addWhere("id",1)->addWhere("name","live%",'like')->select();
        $this->assertTrue(!!$value);
        $value=$this->table->addWhere("id",[1,2],"in")->find();
        $this->assertEquals(2,count($value));
        $value=$this->table->addWhere("id",[1,2],"in")->orderBy("id")->find();
        $this->assertEquals(1,$value[0]['id']);
    }
    public function testUpdate(){
        $this->table->addWhere("id",1)->select();
        $this->table->addWhere("id",1)->update(['intro'=>rand(1,10000)]);
        $this->table->addWhere("id",1)->select();
        $this->table->update(['intro'=>rand(10000,20000)],true);
        $this->table->addWhere("id",1)->select();
    }
    public function testDelete(){
        $value=$this->table->addWhere("id",1)->select();
        $this->assertTrue(!!$value);
        
        $this->table->delete(true);

        $value=$this->table->addWhere("id",1)->select();
        $this->assertFalse(!!$value);
    }
    public function testDelete1(){
        $value=$this->table->addWhere("id",1)->select();
        $this->assertTrue(!!$value);
        $this->table->addWhere("id",1)->delete(true);
        $value=$this->table->addWhere("id",1)->select();
        $this->assertFalse(!!$value);
    }
}
