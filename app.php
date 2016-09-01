<?php
define('APP_PATH', realpath('./'));
require_once(APP_PATH.'/run/RunApp.php');
RunApp::app()->run();
