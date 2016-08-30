<?php
namespace run\phalcon\routers;
use  \Phalcon\Mvc\Router\Group;
class BlogRoutes extends Group{
    public function initialize()
    {
        //Default paths
        $this->setPaths(array(
            'namespace' => 'Blog\\Controllers'
        ));
        $this->setPrefix('/blog');
        $this->add('/{id:[0-9]{5}}', array(
	    'controller'=>'index',
            'action' => 'index'
        ));
    }
}
