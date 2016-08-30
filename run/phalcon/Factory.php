<?php
namespace run\phalcon;
use run\phalcon\LoadRegister;
use run\phalcon\AppRouter;
use \Phalcon\Mvc\application;
use \Phalcon\DI\FactoryDefault;
use \Phalcon\Session\Adapter\Files;
use \Phalcon\Mvc\View;

class Factory{
    private static $_instance;
    private $di;
    private $router;
    private $session;
    private $view;
    private $application;
    
    public static function app(){
	if(!(self::$_instance instanceof self)){
	    self::$_instance = new self;
	}
	return self::$_instance;
    }
    public function run($config){
	LoadRegister::autoRegister($config);
	return $this->application = new application($this->getDi());
    }
    private function getDi(){
	$this->setDi();
	$this->setSession();
	$this->di->set('session',$this->setSession());
	$this->di->set('view', $this->setView());
	$this->di->set('router',$this->setRouter());
	return $this->di;
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
	$this->view->setViewsDir(APP_PATH.'/app/views/');
	$this->view->registerEngines(array(
	    ".phtml" => 'Phalcon\Mvc\View\Engine\Volt'
	));
	return $this->view;
    }
    private function setRouter(){
	$this->router = AppRouter::getRouters();
	return $this->router;
    }
}
?>