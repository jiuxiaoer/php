<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/14 19:39
 */
// 系统 v 消息队列 key ,信号量也要给key 共享存储也要
// 信号量 的用途 : 主要用于多进程/多线程 对共享资源对象的访问控制
// 多个进程 同时对一个公共资源 访问时 就可能引发数据错误
// fork 创 建的进程   业务代码[是使用一条语句来编写的 echo 123;] writer(1,234);
// 这个系统调用函数 程序可以是二进制文件 php[c] --->汇编[多条汇编语句]--->对应多条指令
// 一条高级语言语句编程汇编语句[汇编语句也会对应多条机器指令]
// c/c++
// 多个进程在运行/线程 如果说 某个线程/进程在在执行 加减法运算 []可能执行一半
// 其他进程又对公共资源进行读写 访问  这个时候容易破坏数据
// 多线程 一般是并发执行 【cpu 多核 4核心 4个线程 会同时进行】
// 如果 有对公共资源的访问 如果没有同步处理 很容易造成数据破坏 c/C++ 互斥锁 条件变量 信号量去解决
// system v IPC 信号量非常复杂 posix 信号量

//信号量分类 : 二值信号量 他的值是0和1 可以通过相关函数修改 要么修改为0要么修改为1 这种操作称为pv原语
// p就是减 1 操作 [当前值如果不是1 进程就会阻塞 标识当前的资源改进程不可访问]
// v 就是加1操作 [当前值是0就可以加一操作同时会释放当前资源  让其他进程有机会执行 就有机会访问公共资源]

//计数信号量 计算器,他的值从0到某个最大值
//计算信号量集; 我们可以认为是个数组 系统V信号量 其实就是指信号量集,他的系统调用函数有点复杂
//
// system V 信号量
$file = 'demo36.txt';
$count = 0;
file_put_contents($file, $count);
$key = ftok('demo36.php', 'x');
$sem_id = sem_get($key, 1);// 给1 就行 当成 二值信号量来用

$pid = pcntl_fork();
//子进程
if ($pid === 0) {
    sem_acquire($sem_id);
    $x = (int)file_get_contents($file);
    for ($i = 0; $i < 1000000; $i++) {
        $x += 1;
    }
    file_put_contents($file, $x); //底层的机器指令 不可能是一条 是多条 可能指令只执行一般 被其他进程所打断
    sem_release($sem_id);
    // 数据就破坏了
    exit(0);
}
sem_acquire($sem_id);
$x = (int)file_get_contents($file);

for ($i = 0; $i < 1000000; $i++) {
    $x += 1;
}

file_put_contents($file, $x);
sem_release($sem_id);
//echo file_get_contents($file);