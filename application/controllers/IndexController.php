<?php
use \run\phalcon\BaseController;
use plugins\testClass;
class IndexController extends BaseController{
    public function indexAction(){
        echo "<h1>Hello World</h1>";
        $test = new testClass;
        $test->echoTest();
        echo @self::$request->header['user-agent'];
    }
}

?>