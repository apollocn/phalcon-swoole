<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/1
 * Time: 下午9:49
 */
use \Phalcon\Mvc\Controller;
class HandlerController extends Controller{
    public function notFoundAction(){
        echo "404error page";
    }
}
