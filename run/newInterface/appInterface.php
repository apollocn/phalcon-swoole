<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/11
 * Time: 下午9:35
 */
namespace run\newInterface;
interface appInterface {
    public function start();
    public function stop();
    public function reload();
    public function restart();
    public function status();
}