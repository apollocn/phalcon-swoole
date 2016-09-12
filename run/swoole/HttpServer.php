<?php
namespace run\swoole;
use run\phalcon\Factory;
use \Phalcon\Mvc\application;
use run\phalcon\BaseController;
use run\std;
use run\newInterface\httpInterface;
final class HttpServer implements httpInterface{
    public static $_instance;
    public $http;
    private $app;
    private $std;
    public function run(){
        $this->std = new std;
		$this->http = new \swoole_http_server(\RunApp::$swooleConfig['run']['ip'], \RunApp::$swooleConfig['run']['port']);
			//设置参数
		$this->http->set(
            \RunApp::$swooleConfig['httpServer']
		);
		$this->http->addlistener(\RunApp::$swooleConfig['manager']['ip'],\RunApp::$swooleConfig['manager']['port'],SWOOLE_SOCK_TCP);
		$this->http->on("start",array($this,'onStart'));
		$this->http->on("ManagerStart",array($this,'onManagerStart'));
		$this->http->on('WorkerStart' , array($this , 'onWorkerStart'));
        if(\RunApp::$swooleConfig['run']['runModel']=='PROD'){
            $this->http->on('request' , array($this , 'onRequestProd'));
        }elseif(\RunApp::$swooleConfig['run']['runModel']=='DEV'){
            $this->http->on('request' , array($this , 'onRequestDev'));
        }else{
            $this->http->on('request' ,function($request, $response){
                $response->end('We are currently updating our website , thanks for your visiting!');
            });
        }
		$this->http->on('shutdown',array($this,'onShutdown'));
		$this->http->start();
    }
    public function onShutdown(){

    }
    public function onStart($serv){
        //swoole_set_process_name("swoole_http_server_react");
        $this->std->onStart($serv);
    }
    
    public function onManagerStart($serv){
        //swoole_set_process_name("swoole_http_server_manager");
        $this->std->onManagerStart($serv);
    }
    public function onWorkerStart($serv,$workId) {
		//swoole_set_process_name("swoole_http_server_worker");
		opcache_reset();
		$this->app = new application(Factory::app()->getDi(\RunApp::$phalconConfig));
        $this->std->onWorkerStart($serv,$workId);
    }
    //接受http请求
    public function  onRequestProd($request, $response){
        if($request->server['server_port']==\RunApp::$swooleConfig['manager']['port']){
            $this->bindEvents($request,$response);

            return;
        }
        BaseController::$request = $request;
        BaseController::$response = $response;
        register_shutdown_function([$this,'errorHandle'],$this->app,$response);
        $response->header("Server","phpFrameWork");
        $response->end($this->app->handle($request->server['request_uri'])->getContent());
        return ;
    }
    public function  onRequestDev($request, $response){
        BaseController::$request = $request;
        BaseController::$response = $response;
        register_shutdown_function([$this,'errorHandle'],$this->app,$response);
        $response->header("Server","phpFrameWork");
        $response->end($this->app->handle($request->server['request_uri'])->getContent());
        $this->http->reload();
        return ;
    }
    //监听命令
    private function bindEvents($request,$response){
		if($this->isReload($request)){
            $this->http->reload();
            $response->end();
            return;
		}
		if($this->isShutDown($request)){
			$this->http->shutdown();
            $response->end();
            return;
		}
        $response->end();
    }
    //监听重载命令
    private function isReload($request){
		if(@$request->server['server_port']==\RunApp::$swooleConfig['manager']['port'] && @$request->server['query_string']==\RunApp::$swooleConfig['manager']['reload']){
			return true;
		}
		return false;
    }
    //监听重启命令
    private function isShutDown($request){
		if(@$request->server['server_port']==\RunApp::$swooleConfig['manager']['port'] && @$request->server['query_string']==\RunApp::$swooleConfig['manager']['shutdown']){
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
