<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/25 19:59
 */
var_dump("当前的父进程:==============>".posix_getpid()."=============<");
// fork 一个子进程
$pid=pcntl_fork();
if (0==$pid){

    while(1){
        var_dump("子进程==========>".posix_getpid());
        sleep(2);
    }
    //exit(10);
}
while (1){
    //等待子进程退出  不阻塞主进程
    $pid=pcntl_wait($status,WUNTRACED);
    var_dump("exit pid====>".$pid);
    if ($pid>0){
        //正常退出
        if (pcntl_wifexited($status)){
            var_dump("正常退出=============> id= ".posix_getpid()."退出状态码==".pcntl_wexitstatus($status)."  ");
        }
        //中断退出
        elseif (pcntl_wifsignaled($status)){
            var_dump("中断退出1=============> id= ".posix_getpid()."退出状态码==".pcntl_wtermsig($status)."  ");
        }
        //一般是发送 SIGSTOP SIGTSTP 进程停止
        elseif (pcntl_wifstopped($status)){
            var_dump("中断退出2=============> id= ".posix_getpid()."信号编号==".pcntl_wstopsig($status)."  ");
        }
    }
    var_dump("PID=====>father==",posix_getpid());
    sleep(3);
}
// kill 命令 用来给一个进程发送中断信号 {给进程或者说一个进程组}

//中断信号  有自己的编号 和 对应的信号名称 信号编号以非负数值来表示  信号名字以SIG 开头