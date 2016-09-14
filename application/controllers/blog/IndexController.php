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

        //$this->cache->save("test",$name);
        //$str = $this->cache->get("test",$name)['hello']."\n";

        $this->view->pick('blog/index/index');
	}
}
