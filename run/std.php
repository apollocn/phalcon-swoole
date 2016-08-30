<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace run;

use run\swoole\HttpServer;
class std {
    public $swooleConfig  = [];
    public $phalconConfig = [];
    public function __construct($swooleConfig,$phalconConfig)
    {
        $this->swooleConfig = $swooleConfig;
        $this->phalconConfig = $phalconConfig;
    }


    public function start(){
        HttpServer::app()->run($this->phalconConfig,$this->swooleConfig);
        return;
    }
    public function reload(){

    }
    public function restart(){

    }
    public function stop(){

    }
}
