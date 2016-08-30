<?php
namespace run\swoole;
use run\phalcon\Factory;
class HttpServer {
    public static $_instance;
    private $http;
    private $phalconConfig;
    private $swooleConfig;
    private $application;
    public function run($phalconConfig,$swooleConfig){
		$this->phalconConfig = $phalconConfig;
		$this->swooleConfig = $swooleConfig;
		$this->http = new \swoole_http_server($this->swooleConfig['run']['ip'], $this->swooleConfig['run']['port']);
			//设置参数
		$this->http->set(
			$this->swooleConfig['httpServer']
		);
		$this->http->addlistener('127.0.0.1',$this->swooleConfig['manager']['port'],SWOOLE_SOCK_TCP);

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
    
    public function onWorkerStart() {
		swoole_set_process_name("swoole_http_server_worker");
		$this->setApplication();
    }

    //接受http请求
    public function  onRequest($request, $response){
		ob_start();
		try {
			echo $this->application->handle($request->server['request_uri'])->getContent();
		} catch (Exception  $e) {
			echo "Exception: {$e->getMessage()}\n";
	//	    $logger = new \Phalcon\Logger\Adapter\File("test.log");
	//	    $logger->log($e->getMessage());
	//	    $logger->close();
		}
		$result = ob_get_contents();
		ob_end_clean();
		$response->end($result);

		$this->bindEvents($request);
        return ;
    }
    private function setApplication(){
		$this->application = Factory::app()->run($this->phalconConfig);
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
		if($this->swooleConfig['run']['runModel']=='DEV'){
			return true;
		}
		if(@$request->server['server_port']==$this->swooleConfig['manager']['port'] && @$request->server['query_string']==$this->swooleConfig['manager']['reload']){
			return true;
		}
	return false;
    }
    //监听重启命令
    private function isShutDown($request){
		if(@$request->server['server_port']==$this->swooleConfig['manager']['port'] && @$request->server['query_string']==$this->swooleConfig['manager']['shutdown']){
			return true;
		}
		return false;
    }
    public static function app(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
    }
}
?>
