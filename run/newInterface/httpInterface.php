<?php
/**
 * Created by IntelliJ IDEA.
 * User: anshengli
 * Date: 16/9/11
 * Time: 下午9:28
 */
namespace run\newInterface;
interface httpInterface {
    public function onStart($serv);
    public function onManagerStart($serv);
    public function onWorkerStart($serv,$workId);
    public function onRequest($request, $response);
}