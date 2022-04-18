<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/17 16:02
 */
//发送信号
//1) kill -s 信号编号 | 信号名字 进程PID
//2) 在程序中使用 posix_kill 给一个指定的进程|进程组发送信号
//3) pcntl_alarm SIGALRM
//4) 在终端按下特殊按键 ctrl+z+c+\
//5) 网络 SIGURG ...

pcntl_signal(SIGINT,function ($sig){
    print_r("接收到".$sig."pid ".getmypid()."\n");
});
pcntl_signal(SIGALRM,function ($sig){
    print_r("接收到".$sig."pid ".getmypid()."\n");
});
$mapPid=[];
$pid=pcntl_fork();
if ($pid>0){
    $mapPid[]=$pid;
    $pid=pcntl_fork();
    if ($pid>0){
        $mapPid[]=$pid;
        while (1){
            //1) pid>0
            // pid进程标识
//            posix_kill($mapPid[0],SIGINT);
            //2) pid=0 向进程组中的每个进程发送信号
            //posix_kill(0,SIGINT);
            //3) pid =-1 的时候 干掉所有服务进程
            //posix_kill(-1,SIGINT);
            sleep(2);
        }
    }
}
//这里是子进程的可运行的代码
while (1){
    pcntl_signal_dispatch();
    print_r("当前PID".getmypid()." 父进程 ".posix_getppid()." 进程组 ".posix_getpgrp()."\n");
    sleep(1);
}