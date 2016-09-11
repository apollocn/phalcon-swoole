<?php
use \Phalcon\Config\Adapter\Ini;
use run\swoole\HttpServer;
interface runInterFace {
    public function start();
}
final class RunApp implements runInterFace{
    private $argv = ['start','stop','reload','restart','status'];
    private static $_instance;
    public function __construct() {
        spl_autoload_register($this->autoload());
        HttpServer::$swooleConfig = $this->objToArr(new Ini(APP_PATH.'/config/swoole.ini'));
        HttpServer::$phalconConfig = $this->objToArr(new Ini(APP_PATH.'/config/phalcon.ini'));
    }
    public static function app(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self;
	    }
	    return self::$_instance;
    }
    public function run(){
        foreach($this->argv as $val){
            if($val != @$_SERVER['argv'][1]){
                continue;
            }
            $this->$val();
        }
	    return ;
    }
    public function start(){
        HttpServer::app()->run();
        return;
    }

    private function objToArr($obj){
        $_arr = is_object($obj) ? get_object_vars($obj) :$obj;
        $arr =[];
        foreach ($_arr as $key=>$val){
            $val = (is_array($val) || is_object($val)) ? $this->objToArr($val):$val;
            $arr[$key] = $val;
        }
        return $arr;
    }
    private function autoload(){
        return function($class){
            $file = str_replace('\\', '/', $class);
            $file = APP_PATH.DIRECTORY_SEPARATOR.$file.'.php';
            if(file_exists($file)){
                require_once($file);
            }
        };
    }
}
