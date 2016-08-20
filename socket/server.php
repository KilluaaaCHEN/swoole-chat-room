<?php

/**
 * Created by PhpStorm.
 * User: larry
 * Date: 16/8/17
 * Time: 11:17
 */
$server = new swoole_websocket_server("0.0.0.0", 9501);
$server->set(array(
    'task_worker_num' => 1,//task进程数量
    'dispatch_mode' => 2,
    'debug_mode' => 1,
    'daemonize' => false,//是否守护进程
    'log_file' => __DIR__ . '/log/webs_swoole.log',
));

$server->on('finish', function ($serv, $task_id, $data) {

});
$server->on('open', function (swoole_websocket_server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->task(['msg' => $frame->data, 'cu_fd' => $frame->fd]);
});
$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

//异步队列发消息
$server->on('task', function ($serv, $task_id, $from_id, $data) {
    foreach ($serv->connections as $fd) {
        if ($fd != $data['cu_fd']) {
            $serv->push($fd, $data['msg']);
        }
    }
});
$server->start();

