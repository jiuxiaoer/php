<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/29 19:06
 */
// 进程调度
// pcntl_fork 创建一个子进程  这个时候就会存在父进程和子进程
// cpu 先调度那个进程

// 一般来说 父进程的 cpu 调度要更高一些
// 为了不让子进程 变成孤儿 进程 一般先让父进程 sleep 几秒 或者 pcntl_wait 阻塞住

//进程的观察命令  top
//在linux系统中 一般把进程/线程 称为任务Task
//进程号  那个用户启动                                            运行了多少时间  什么命令启动
// PID     USER      PR  NI    VIRT    RES    SHR S %CPU %MEM     TIME+     COMMAND
// PR priority 优先级
// NI nice 进程的nice 值  nice 值越小 则优先级越高
// 进程的 nice 值越小, 则进程的 优先级PR 越高, cpu 就先调度这个进程
$nice=$argv[1];
$start=time();
$count=0;

$pid = pcntl_fork();
if ($pid==0){
    print_r("child pid=".posix_getpid().'nice=='.pcntl_getpriority()."\n");
    //pcntl_setpriority($nice,getmypid(),PRIO_PROCESS);
    //print_r("child pid=".posix_getpid().'nice=='.pcntl_getpriority()."\n");
    while (1){
        $count++;
        if (time()-$start>5){
            break;
        }
    }
}else{
    print_r("parent pid=".posix_getpid().'nice=='.pcntl_getpriority()."\n");
    pcntl_setpriority($nice,getmypid(),PRIO_PROCESS);
    print_r("parent pid=".posix_getpid().'nice=='.pcntl_getpriority()."\n");
    while (1){
        $count++;
        if (time()-$start>5){
            break;
        }
    }
}
print_r("pid=".posix_getpid().'nice=='.pcntl_getpriority()."coutn==".$count."\n");