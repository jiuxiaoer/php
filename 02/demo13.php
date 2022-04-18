<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/16 13:43
 */
// 一个程序启动 之后 就是一个进程   进程的数据肯定是在内存中
// 正文段+数据段  内存中的一些数据 也会写入到 proc 文件系统中

// proc/PID
//1) ps ,PID,PPID,UID,GID,STAT,COMMAND
//2) top
//3) pstree

//proc/PID
//linux 中一般会把线程进程称为任务 task
//TTY 是一个物理终端   伪终端 pts/0 ssh/telnet
echo posix_getpid();
print_r(getenv());
$fd=fopen('/demo13.txt','w');
//打印进程限制
print_r(posix_getrlimit());
print_r(posix_getlogin());
while (1){
    ;
}