<?php
namespace run\phalcon;
use \Phalcon\Mvc\Router;
use run\phalcon\routers;
class AppRouter{
    private static $router;
    public static function getRouters(){
	self::$router = new Router();
	self::$router->mount(new routers\NewsRouters);
	self::$router->mount(new routers\BlogRoutes);
	return self::$router;
    }
}


?>