<?php
define('APP_PATH', realpath('./'));
ini_set("error_log",'/mnt/hgfs/heheda/phalcon-swoole/test.log');
require_once(APP_PATH.'/run/runApp.php');
runApp::app()->run();
