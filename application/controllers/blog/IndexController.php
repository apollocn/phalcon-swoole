<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Blog;
use \run\phalcon\BaseController;
class IndexController extends BaseController {
	public function indexAction(){
		echo "i am Blog controllers\n";
        echo $this->dispatcher->getParam('id')."\n";
        $name = array('hello'=>'world');
        for ($i=0;$i<6;$i++){
            $this->cache->save("test".$i,$name);
            echo $this->cache->get("test".$i,$name)['hello']."\n";
            if($this->ifRefresh()){
                $this->cache->delete('test');
            }
            echo $this->cache->get("test".$i,$name)['hello']."\n";
        }
        for ($i=0;$i<5;$i++){
            $name = array('tom','lil','asdnfo','asdmif');
            $de = json_encode($name);
            print_r( json_decode($de,true));
        }
	}
}
