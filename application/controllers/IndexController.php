<?php
use \run\phalcon\BaseController;
use plugins\testClass;
class IndexController extends BaseController{
    public function indexAction(){
        $test = new testClass();
        $test->echoTest();
//        $this->dispatcher->forward(
//            array(
//                "controller" => "\blog\index",
//                "action"     => "index"
//            )
//        );
        $this->view->user =  @self::$request->header['user-agent'];
    }
}

?>