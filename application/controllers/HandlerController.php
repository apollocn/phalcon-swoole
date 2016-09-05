<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/1
 * Time: 下午9:49
 */
use \Phalcon\Mvc\Controller;
class HandlerController extends Controller{
    protected $body;
    protected $head;
    protected $cookie;
    public function notFoundAction(){
        echo "404 error page";
    }
    public function errorAction(){
        $error = error_get_last();
        var_dump($error);
    }
}
