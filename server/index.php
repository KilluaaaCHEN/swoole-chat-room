<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 16/8/18
 * Time: 22:14
 */
//$serv = new swoole_server("127.0.0.1", 9501);
//$serv->on('connect', function ($serv, $fd){
//    echo "Client:Connect.\n";
//});
//$serv->on('receive', function ($serv, $fd, $from_id, $data) {
//    $serv->send($fd, 'Swoole: '.$data);
//    var_dump($serv->connection_info($fd));
////    $serv->close($fd);
//});
//$serv->on('close', function ($serv, $fd) {
//    echo "Client: Close.\n";
//});
//
//$serv->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);//监听其它端口
//
//$serv->start();


$server = new swoole_server('127.0.0.1', 9501);

$process = new swoole_process(function ($process) use ($server) {
    while (true) {
        $msg = $process->read();
        foreach ($server->connections as $conn) {
            $server->send($conn, 'data:' . $msg);
        }
    }
});

$server->addProcess($process);

$server->on('receive', function ($serv, $fd, $from_id, $data) use ($process) {
    //群发收到的消息
    $process->write($data);
});

$server->start();