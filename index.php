<?php
//启动http server
$http = new swoole_http_server("0.0.0.0", 9502);

$http->set(array(
    'worker_num' => 8,   //工作进程数量
    'daemonize' => false, //是否作为守护进程
    'heartbeat_check_interval' => 60,
    'heartbeat_idle_time' => 600,
    'open_tcp_nodelay' => true,
    'log_file' => '/tmp/swoole_http_server.log',
));

$globalRes = '';
define('BASEDIR',__DIR__);
require 'vendor/autoload.php';
require './SasPHP/SasPHP.php';

$http->on('request', function ($request, $response) use($globalRes) {
    // 阻止google浏览器的ico请求
    if($request->server['request_uri'] == '/favicon.ico'){
        $response->end();exit;}

    $globalRes = $response;
    $_SERVER = $request->server;
    $res = SasPHP\SasPHP::start();
    $response->end($res);
});

$http->start();