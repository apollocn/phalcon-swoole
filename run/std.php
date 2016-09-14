<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/11
 * Time: 下午7:47
 */
namespace run;
class std {
    private $color = [
        'black' => '\033[30m',
        'red' => '\033[31m',
        'green' => '\033[32m',
        'yellow' => '\033[33m',
        'blue' => '\033[34m',
        'purple' => '\033[35m',
        'darkGreen' => '\033[36m',
        'white' => '\033[37m',
    ];
    private $clear = "\e[0m";
    private $newLine = "\n";
    private function writeLine ($string,$args){
        if(!is_string($string)||!is_array($args)){
            return;
        }
        $str = stripcslashes($string).$this->newLine;
        array_unshift($args,$str);
        $text = call_user_func_array('sprintf', $args);
        $fh = fopen('php://stdout', 'w');
        fwrite($fh, $text);
        fclose($fh);
        return ;
    }
    private function writeLog($str){
        $fh = fopen(APP_PATH.\RunApp::$swooleConfig['manager']['log'], "a");
        fwrite($fh, $str);
        fclose($fh);
    }
    private function appendStr($str,$color){
        if(!isset($this->color[$color])){
            return $this->color['white'].$str.$this->clear;
        }
        return $this->color[$color].$str.$this->clear;
    }
    public function onStart($serv){
        $time = "[".date("Y-m-d H:i:s",time())."]  ";
        $str = $this->appendStr($time,'darkGreen');
        $str.= "master running on pid ";
        $str.= $this->appendStr("%d",'green');
        $args = [$serv->master_pid];
        $this->writeLine($str,$args);
        $this->writeLog($time."master running on pid ".$serv->master_pid."\n");
        return ;
    }
    public function onManagerStart($serv){
        $time = "[".date("Y-m-d H:i:s",time())."]  ";
        $str = $this->appendStr($time,'darkGreen');
        $str.= 'manager running on pid ';
        $str.= $this->appendStr("%d",'green');
        $args = [$serv->manager_pid];
        $this->writeLine($str,$args);
        $this->writeLog($time."manager running on pid ".$serv->manager_pid."\n");
        return ;
    }
    public function onWorkerStart($serv,$workId){
        $time = "[".date("Y-m-d H:i:s",time())."]  ";
        $str = $this->appendStr($time,'darkGreen');
        $str.= "worker running on pid ";
        $str.= $this->appendStr("%d",'green');
        $str.= " by id ";
        $str.= $this->appendStr("%d ",'green');
        $args = [$serv->worker_pid,$workId];
        $this->writeLine($str,$args);
        $this->writeLog($time."worker running on pid ".$serv->worker_pid." by id ".$workId."\n");
        return ;
    }
    public function needInstall($requires){
        $need = [];
        for($i=0;$i<count($requires);$i++){
            $need[]= "you need install ".$this->appendStr("%s",'green');
        }
        $str = implode("\n",$need);
        $this->writeLine($str,$requires);
        die();
    }
}