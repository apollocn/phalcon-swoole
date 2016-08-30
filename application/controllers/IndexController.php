<?php
use \Phalcon\Mvc\Controller;
use Plugins as PL;
class IndexController extends Controller{
    public function indexAction(){
	echo "<h1>Hello World</h1>";
	$test = new PL\test;
	$test->test();
    }
}

?>