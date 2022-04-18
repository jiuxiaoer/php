<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/27 14:54
 */
// exec 函数的功能 用来执行一个程序
// pcntl 的进程拓展 pcntl_exec 它内部的系统调用函数 execve 函数
// exec 一般的用法是 父进程先创建一个子进程 让后子进程 调用这个函数
// 正文段[代码段] + 数据段 会被新程序替换 他的一些属性会继承父进程 PID 不会发生变化
function show($str){
    $pid=pcntl_fork();
    fprintf(STDOUT,"%s  pid=%d,ppid=%d,gpid=%d,sid=%d,uid=%d,gid=%d\n",
    $str,
    $pid,
    posix_getppid(),
    posix_getpgrp(),
    posix_getsid($pid),
    posix_getuid(),
    posix_getgid()
    );
}
show("parent");
$pid=pcntl_fork();
if (0==$pid){
    show("child");
//    pcntl_exec("/www/server/php/73/bin/php",['demo9_1.php',1,2,3],['test']);
    pcntl_exec('demo9_2',["a",'b','c']);
    echo 'hello word';
}
$pid=pcntl_wait($status);
if ($pid>0){
    var_dump("exit pid%d".$pid);
}