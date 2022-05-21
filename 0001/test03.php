<?php
require 'Scheduler.php';
require 'SystemCall.php';
function waitForRead($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForRead($socket, $task);
        }
    );
}
function waitForWrite($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForWrite($socket, $task);
        }
    );
}

function newTask(Generator $coroutine) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine)); //下次向任务内传递的内容 创建了 一个新的子任务 并向父任务发送了子协程id
            $scheduler->schedule($task); // 父任务入队
        }
    );
}
function server($port) {
    echo "Starting server at port $port...\n";
    //使用stream socket 创建一个 tcp 服务
    $socket = @stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
    if (!$socket) throw new Exception($errStr, $errNo);
    stream_set_blocking($socket, 0);
    while (true) {
        yield waitForRead($socket);
        $clientSocket = stream_socket_accept($socket, 0);
        yield newTask(handleClient($clientSocket));
    }
}

function handleClient($socket) {
    yield waitForRead($socket);
    $data = fread($socket, 8192);

    $msg = "Received following request:\n\n$data";
    $msgLength = strlen($msg);

    $response = <<<res
HTTP/1.1 200 OK
Content-Type: text/html

$msg
res;

    yield waitForWrite($socket);
    fwrite($socket, $response);

    fclose($socket);
}
//创建一个任务调度器
$scheduler = new Scheduler;
// 创建一个服务端资源
$scheduler->newTask(server(8000));
$scheduler->run();