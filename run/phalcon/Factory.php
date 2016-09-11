<?php
namespace run\phalcon;

use \Phalcon\DI\FactoryDefault;
use \Phalcon\Session\Adapter\Files;
use \Phalcon\Mvc\View;
use \Phalcon\Crypt;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;

class Factory {
    private static $_instance;
    private $router;
    private $session;
    private $view;
    private $crypt;
    private $cache;
    private $di;

    public static function app(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getDi($config){
        LoadRegister::autoRegister($config);
        $this->setDi();
        $this->di->set('session',$this->setSession());
        $this->di->set('view', $this->setView());
        $this->di->set('router',$this->setRouter());
        $this->di->set('crypt', $this->setCrypt());
        $this->di->set('cache', $this->setCache());
        return $this->di;
    }
    public function setCrypt(){
        $this->crypt = new Crypt();
        $this->crypt->setKey('FNnlkafoiasdfNSLK');
        return $this->crypt;
    }
    private function setDi(){
        $this->di = new FactoryDefault();
        return;
    }
    private function setSession(){
	    $this->session = new Files();
	    $this->session->start();
        return $this->session;
    }
    private function setView(){
        $this->view = new View();
        $this->view->setViewsDir(APP_PATH.'/application/views/');
        $this->view->registerEngines(array(
	        ".phtml" => 'Phalcon\Mvc\View\Engine\Volt'
	    ));
	    return $this->view;
    }
    private function setRouter(){
        $this->router = AppRouter::getRouters();
        return $this->router;
    }
    private function setCache(){
        $frontCache = new FrontData(
            array(
                "lifetime" => 1800
            )
        );
        $this->cache = new BackMemCached(
            $frontCache,
            array(
                "servers" => array(
                    array(
                        "host"   => "127.0.0.1",
                        "port"   => "11211",
                    )
                )
            )
        );
        return $this->cache;
    }

}
?>