<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/1
 * Time: 下午9:53
 */
use \Phalcon\Mvc\Controller;
class ManagerController extends Controller{
    public function queryAction(){
        echo "this is manger controller , query method";
    }
}