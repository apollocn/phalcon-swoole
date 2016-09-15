<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/11
 * Time: 下午10:59
 */
namespace run;
use run\swoole\HttpServer;
use run\newInterface\appInterface;
class cliManage implements appInterface {
    public $cli ;

    public function start(){
        HttpServer::app()->run();
        return;
    }
    public function stop(){
        $this->curl(\RunApp::$swooleConfig['manager']['shutdown']);
    }
    public function reload(){
        $this->curl(\RunApp::$swooleConfig['manager']['reload']);
    }
    public function help(){
        $std = new std();
        $std->help();
    }
    public function curl($str){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:".\RunApp::$swooleConfig['manager']['port']."/?".$str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }
}