<?php
namespace run\phalcon;
use \Phalcon\Loader;
class LoadRegister{
    private static $loader;
    public static function autoRegister($config){
        self::$loader = new Loader();
        self::$loader -> registerDirs(
            self::appendPath($config['applicationDir'])
        )->register();
        self::$loader->registerNamespaces(
            self::appendPath($config['namespace'])
        );
        return;
    }
    private static function appendPath($config){
        foreach($config as $key =>$val){
            $result[$key] = APP_PATH.$val;
        }
	    return $result;
    }
}
?>