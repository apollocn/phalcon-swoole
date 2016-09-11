<?php
namespace run\swoole;
use run\phalcon\Factory;
use \Phalcon\Mvc\application;
use run\phalcon\BaseController;
final class HttpServer {
    public static $_instance;
    private $http;
    private $master_pid;
    public static $phalconConfig;
    public static $swooleConfig;
    private $app;
    public function run(){
		$this->http = new \swoole_http_server(self::$swooleConfig['run']['ip'], self::$swooleConfig['run']['port']);
			//设置参数
		$this->http->set(
			self::$swooleConfig['httpServer']
		);
		$this->http->addlistener('127.0.0.1',self::$swooleConfig['manager']['port'],SWOOLE_SOCK_TCP);

		$this->http->on("start",array($this,'onStart'));

		$this->http->on("ManagerStart",array($this,'onManagerStart'));

		$this->http->on('WorkerStart' , array($this , 'onWorkerStart'));

		$this->http->on('request' , array($this , 'onRequest'));

		$this->http->on('shutdown',array($this,'shutdown'));


		$this->http->start();
    }
    public function shutdown(){

    }
    public function onStart(){
		swoole_set_process_name("swoole_http_server_react");
    }
    
    public function onManagerStart(){
		swoole_set_process_name("swoole_http_server_manager");
    }
    
    public function onWorkerStart($serv) {
		swoole_set_process_name("swoole_http_server_worker");
		opcache_reset();
    	$this->master_pid = $serv->master_pid;
		$this->app = new application(Factory::app()->getDi(self::$phalconConfig));
    }
    //接受http请求
    public function  onRequest($request, $response){
		BaseController::$request = $request;
		BaseController::$response = $response;
		register_shutdown_function([$this,'errorHandle'],$this->app,$response);
		$response->header("Server","phpFrameWork");
        $response->end($this->app->handle($request->server['request_uri'])->getContent());
		$this->bindEvents($request);
        return ;
    }
    //监听命令
    private function bindEvents($request){
		if($this->isReload($request)){
			$this->http->reload();
		}
		if($this->isShutDown($request)){
			$this->http->shutdown();
		}
    }
    //监听重载命令
    private function isReload($request){
		if(self::$swooleConfig['run']['runModel']=='DEV'){
			return true;
		}
		if(@$request->server['server_port']==self::$swooleConfig['manager']['port'] && @$request->server['query_string']==self::$swooleConfig['manager']['reload']){
			return true;
		}
		return false;
    }
    //监听重启命令
    private function isShutDown($request){
		if(@$request->server['server_port']==self::$swooleConfig['manager']['port'] && @$request->server['query_string']==self::$swooleConfig['manager']['shutdown']){
			return true;
		}
		return false;
    }
    //捕获错误
    public function errorHandle($application,$response){
		$response->end($application->handle('/error')->getContent());
	}
    public static function app(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
    }
}
?>
