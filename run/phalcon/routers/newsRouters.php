<?php
namespace run\phalcon\routers;
use  \Phalcon\Mvc\Router\Group;
class newsRouters extends Group {
    public function initialize(){
	    $this->addGet("/news/{year:[0-9]+}","news::detail");
	    $this->addGet("/admin/{manger:[a-z]+}","\Admin\Manager::query");
    }
}