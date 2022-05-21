<?php

/**
 * 任务和调度器之间的通信
 */
class SystemCall {
    // 回调执行的任务
    protected $callback;
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    //当尝试以调用函数的方式调用一个对象时，__invoke() 方法会被自动调用。
    public function __invoke(Task $task, Scheduler $scheduler) {
        //
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}