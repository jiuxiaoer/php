<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/17 14:17
 */
require 'Scheduler.php';
require 'SystemCall.php';
function getTaskId() {
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId()); // 下次向任务内传递信息
            $scheduler->schedule($task); //传递信息了 再次入队
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
function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->killTask($tid)); //发送给任务的
            $scheduler->schedule($task);
        }
    );
}

function childTask() {
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}
// yield 左边 是传递给外面的 本程序中是传给调度器的
// yield 右边的变量是接受 下次任务的->send 一般在传递给调度器里的SystemCall函数写入
function task() {
    $tid = (yield getTaskId()); //向调度器传递了一个 SystemCall 发送任务id的
    $childTid = (yield newTask(childTask())); //向调度器传递了 一个 SystemCall 创建子任务的
    $flog=false;
    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
        if ($i == 3) $flog =yield killTask($childTid);
        if ($flog) echo "$childTid is kill~~~~~".PHP_EOL;
    }
}
$scheduler = new Scheduler;
$scheduler->newTask(task());
$scheduler->run();
?>