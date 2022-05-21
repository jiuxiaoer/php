<?php
require "Task.php";

/**
 * 任务调度器
 */
class Scheduler
{
    //最大任务 ID
    protected $maxTaskId = 0;
    //任务 map
    protected $taskMap = []; // taskId => task
    //任务队列
    protected $taskQueue;
    // resourceID => [socket, tasks]
    protected $waitingForRead = [];
    protected $waitingForWrite = [];

    public function __construct() {
        $this->taskQueue = new SplQueue();
    }

    //加入一个任务
    public function newTask(Generator $coroutine) {
        //从 1 开始
        $tid = ++$this->maxTaskId;
        // 创建一个 Task
        $task = new Task($tid, $coroutine);
        // 放进 任务 map
        $this->taskMap[$tid] = $task;
        // 放进队列
        $this->schedule($task);
        // 返回任务 id
        return $tid;
    }

    public function schedule(Task $task) {
        //入队
        $this->taskQueue->enqueue($task);
    }

    public function run() {
        $this->newTask($this->ioPollTask());
        // 任务队列不为空 就一直执行
        while (!$this->taskQueue->isEmpty()) {
            // 先进先出 出队
            $task = $this->taskQueue->dequeue();
            // 执行任务 执行到 yield 任务就会被中断
            $retval = $task->run();
            //
            if ($retval instanceof SystemCall) {
                //传进当前任务 和调度器
                //以函数的方式调用对象
                $retval($task, $this);
                continue;
            }
            // 判断是否还有下一个 yield
            if ($task->isFinished()) {
                // 销毁 Map 的任务
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                //还有 yield 继续排队执行
                $this->schedule($task);
            }
        }
    }

    //杀死任务
    public function killTask($tid) {
        if (!isset($this->taskMap[$tid])) {
            //任务已经被释放
            return false;
        }
        //释放任务
        unset($this->taskMap[$tid]);
        // This is a bit ugly and could be optimized so it does not have to walk the queue,
        // but assuming that killing tasks is rather rare I won't bother with it now
        //从队列释放任务
        foreach ($this->taskQueue as $i => $task) {
            if ($task->getTaskId() === $tid) {
                unset($this->taskQueue[$i]);
                break;
            }
        }
        return true;
    }

    public function waitForRead($socket, Task $task) {
        if (isset($this->waitingForRead[(int)$socket])) {
            $this->waitingForRead[(int)$socket][1][] = $task;
        } else {
            $this->waitingForRead[(int)$socket] = [$socket, [$task]];
        }
    }

    public function waitForWrite($socket, Task $task) {
        if (isset($this->waitingForWrite[(int)$socket])) {
            $this->waitingForWrite[(int)$socket][1][] = $task;
        } else {
            $this->waitingForWrite[(int)$socket] = [$socket, [$task]];
        }
    }

    protected function ioPoll($timeout) {
        $rSocks = [];
        foreach ($this->waitingForRead as list($socket)) {
            $rSocks[] = $socket;
        }
        $wSocks = [];
        foreach ($this->waitingForWrite as list($socket)) {
            $wSocks[] = $socket;
        }
        $eSocks = []; // dummy
        if (count($rSocks)==0&&count($wSocks)==0){
            return;
        }
        if (!stream_select($rSocks, $wSocks, $eSocks, $timeout)) {
            return;
        }
        foreach ($rSocks as $socket) {
            list(, $tasks) = $this->waitingForRead[(int)$socket];
            unset($this->waitingForRead[(int)$socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
        foreach ($wSocks as $socket) {
            list(, $tasks) = $this->waitingForWrite[(int)$socket];
            unset($this->waitingForWrite[(int)$socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
    }

    protected function ioPollTask() {
        while (true) {
            if ($this->taskQueue->isEmpty()) {
                $this->ioPoll(null);
            } else {
                $this->ioPoll(0);
            }
            yield;
        }
    }
}
