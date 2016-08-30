<?php
use \Phalcon\Config\Adapter\Ini;
use run\std;
class RunApp {
    private $argv = ['start','stop','reload','restart','status'];
    private static $_instance;
    private $std = [];
    public function __construct() {
        spl_autoload_register($this->autoload());
        $swooleConfig = $this->objToArr(new Ini(APP_PATH.'/config/swoole.ini'));
        $phalconConfig = $this->objToArr(new Ini(APP_PATH.'/config/phalcon.ini'));
        $this->std = new std($swooleConfig,$phalconConfig);

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
            $this->std->$val();
        }
	    return ;
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
            }else{
                echo $file;exit;
            }
        };
    }
}
