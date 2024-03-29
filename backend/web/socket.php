<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-17
 * Time: 下午4:17
 */
require(__DIR__ . '/../../vendor/autoload.php');
use Workerman\Worker;
$global_uid = 0;

// 当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handler_connection($connection)
{
    global $text_worker, $global_uid;
    // 为这个链接分配一个uid
    $connection->uid = ++$global_uid;
    //echo "Connect ok......";
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("Me(".$conn->uid.") user[{$connection->uid}] said: $data");
    }
    //$connection->send("you send a message: ".$data);
    echo $connection->uid."send {$data} at ".date('y-m-d H:i:s',time());
}

// 当客户端断开时，广播给所有客户端
function handle_close($connection)
{
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("user[{$connection->uid}] logout");
    }
}

// 创建一个文本协议的Worker监听2347接口
$text_worker = new Worker("websocket://127.0.0.1:2347");

// 只启动4个进程，这样方便客户端之间传输数据
$text_worker->count = 4;

$text_worker->onConnect = 'handler_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';

Worker::runAll();