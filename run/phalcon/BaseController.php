<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/9
 * Time: 下午10:01
 */
namespace run\phalcon;
use \Phalcon\Mvc\Controller;

class BaseController extends Controller {
    public static $request;
    public static $response;
    public function initialize(){

    }
    public function ifRefresh(){
        if(@self::$request->get['refreshCache']==1){
            return true;
        }
        return false;
    }
}