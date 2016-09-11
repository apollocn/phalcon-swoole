<?php
use \run\phalcon\BaseController;
class NewsController extends BaseController{
    public function detailAction(){
    	$year = $this->dispatcher->getParam("year");
        echo $year;
    }
}
?>