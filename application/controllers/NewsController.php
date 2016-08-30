<?php
use Phalcon\Mvc\Controller;
class NewsController extends Controller{
    public function detailAction(){
    	$year = $this->dispatcher->getParam("year");
        echo $year;
    }
}
?>