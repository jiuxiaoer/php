<?php
/**
 * Task任务类
 */
class Task
{
    //任务ID
    protected $taskId;
    //生成器
    protected $coroutine;
    //第一次需要调用current来获取值 否则获取的是第二个
    protected $beforeFirstYield = true;
    //向协程函数发送值
    protected $sendValue;

    /**
     * 任务初始化
     * Task constructor.
     * @param $taskId
     * @param Generator $coroutine
     */
    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }
    /**
     * 获取当前的Task的ID
     *
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
    /**
     * 判断Task执行完毕了没有
     *
     * @return bool
     */
    public function isFinished()
    {
        return !$this->coroutine->valid();
    }
    /**
     * 设置下次要传给协程的值，比如 $id = (yield $xxxx)，这个值就给了$id了
     *
     * @param $value
     */
    public function setSendValue($value)
    {
        $this->sendValue = $value;
    }
    /**
     * 运行任务
     *
     * @return mixed
     */
    public function run()
    {
        // 这里要注意，生成器的开始会reset，所以第一个值要用current获取
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            // 我们说过了，用send去调用一个生成器
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
}