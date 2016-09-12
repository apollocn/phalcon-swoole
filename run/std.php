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
    private function appendStr($str,$color){
        if(!isset($this->color[$color])){
            return $this->color['white'].$str.$this->clear;
        }
        return $this->color[$color].$str.$this->clear;
    }
    public function onStart($serv){
        $str = "master running on pid ";
        $str.= $this->appendStr("%d",'green');
        $args = [$serv->master_pid];
        $this->writeLine($str,$args);
        return ;
    }
    public function onManagerStart($serv){
        $str = 'manager running on pid ';
        $str.= $this->appendStr("%d",'green');
        $args = [$serv->manager_pid];
        $this->writeLine($str,$args);
        return ;
    }
    public function onWorkerStart($serv,$workId){
        $str = "worker running on pid ";
        $str.= $this->appendStr("%d",'green');
        $str.= " by id ";
        $str.= $this->appendStr("%d ",'green');
        $args = [$serv->worker_pid,$workId];
        $this->writeLine($str,$args);
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