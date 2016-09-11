<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/1
 * Time: 下午9:53
 */
namespace Admin;
use \run\phalcon\BaseController;
class ManagerController extends BaseController{
    public function queryAction(){
        echo "this is manger controller , query method";
    }
}