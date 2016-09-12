<?php
use \Phalcon\Config\Adapter\Ini;
use run\std;
use run\cliManage;
final class RunApp {
    private $argv = ['start','stop','reload','restart','status'];
    private $requireMent = ['phalcon','swoole','Zend OPcache','memcached'];
    private static $_instance;
    public static $swooleConfig;
    public static $phalconConfig;
    private $manage;
    public function __construct() {
        spl_autoload_register($this->autoload());
        $this->requireMents();
        self::$swooleConfig = $this->objToArr(new Ini(APP_PATH.'/config/swoole.ini'));
        self::$phalconConfig = $this->objToArr(new Ini(APP_PATH.'/config/phalcon.ini'));
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
            $this->manage = new cliManage();
            $this->manage->$val();
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
            }
        };
    }
    private function requireMents(){
        $ext = get_loaded_extensions();
        $std = new std();
        $need = [];
        foreach ($this->requireMent as $val){
            if(!in_array($val,$ext)){
                $need[] = $val;
            }
        }
        if(count($need)>0){
            $std->needInstall($need);
        }
        return;
    }
}