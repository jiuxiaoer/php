<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/22 20:34
 */
function showPID()
{
    $pid = posix_getpid();
    fprintf(STDOUT,"pid=%d,ppid=%d,pgid=%d,sid=%d\n",$pid,posix_getppid(),posix_getpgid($pid),posix_getsid($pid));

}

$pid=pcntl_fork();
showPID();
while (1){
    sleep(1);
}

// win [xshell]
// tcp/ip
// linux sshd 服务
// sshd 进程 打开 ptmx 伪终端主设备文件
// 伪终端设备驱动文件
// bin/bash 进程 pts[0,1,2]


// pts 伪终端设备驱动程序