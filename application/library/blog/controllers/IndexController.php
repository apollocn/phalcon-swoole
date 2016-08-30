<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Blog\Controllers;
use Phalcon\Mvc\Controller;
class IndexController extends Controller{
	public function indexAction(){
		echo "i am Blog controller\n";
	}
}
