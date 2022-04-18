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

pcntl_signal(SIGCHLD, function ($sig) {
    print_r("接收到" . $sig . "pid " . getmypid() . "\n");
//    pcntl_alarm(2);
    $pid = pcntl_waitpid(-1,$status,WNOHANG);
    if ($pid>0){
        print_r("pid " . getmypid()."退出了" . "\n");
    }
});
//// 时间到后 定时被清理
//pcntl_alarm(1);
//pcntl_alarm(3);
$pcntl_fork = pcntl_fork();
if ($pcntl_fork > 0) {
    while (1) {
        pcntl_signal_dispatch();
        print_r("当前PID" . getmypid() . " 父进程 " . posix_getppid() . " 进程组 " . posix_getpgrp() . "\n");
        sleep(1);
    }
} else {
    print_r("子进程结束");
}