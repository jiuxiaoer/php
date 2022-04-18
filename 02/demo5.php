<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/20 18:46
 */
// 进程控制

//程序被加载到内存 进程  分配信息 pid  ppid euid pgid gid egid
// pid
// ps -exj
//  PPID   PID  PGID   SID TTY      TPGID STAT   UID   TIME COMMAND
//R 运行状态
//Z 僵尸状态
//S 睡眠装填
//T 停止状态

function printId() {
    fprintf(STDOUT, "pid=%s\n", posix_getpid());//自己的进程id
    fprintf(STDOUT, "ppid=%s\n", posix_getppid());//父进程的id
    fprintf(STDOUT, "pgid=%s\n", posix_getsid(posix_getpid()));//进程组的id
    fprintf(STDOUT, "pgid=%s\n", posix_getpgrp());//进程组的id 会话id
    fprintf(STDOUT, "uid=%s\n", posix_getuid());//用户id  当前登录用户
    fprintf(STDOUT, "gid=%s\n", posix_getgid());//组id
    fprintf(STDOUT, "egid=%s\n", posix_getegid());//有效用户id
    fprintf(STDOUT, "euid=%s\n", posix_geteuid());//有效用户id
} fprintf(STDOUT, "现在我的进程标识%d\n",posix_getpid());
//这个函数会返回两次 [会执行两次 第一次可能是父进程 第二次可能是子进程  ]
$pid = pcntl_fork();//fork 之后 父进程运行
//本行会有两个进程运行 分别是子进程 父进程 a b 父进程29行运行  子进程30行
//子进程前面的代码不会运行  下面子进程运行

//父进程从29行 fork函数开始
//函数执行成功后执行一个子进程 子进程会 {复制父进程的代码段和数据段  ELF文件的结构}
//然后父进程继续执行 41行  让后进程结束  子进程从41 fprint后结束行开始运行进程结束
//当父进程调用pcntl_fork之后创建的子进程，那么 父进程和子进程那个先运行  无法确定  是由系统决定
//一般都是父进程先运行 如果说父进程先结束  子进程就没有父亲  就成为了孤儿进程
//会被 1号进程接管  所以让父进程后结束  变成孤儿进程的后果就是 跑到回台运行了
//当子进程被创建后 会复制 【写时复制】  父子进程 共同使用同一块内存空间
//当子进程修改内存空间时 系统会复制新的内存空间给 子进程修改  //子进程得到的 pid 结果是 0
fprintf(STDOUT, "pid=%s,ppid=%d\n", posix_getpid(),posix_getppid());//自己的进程id
//if ($pid==0){
//
//}//这一行结束
//while (1){
//    ;
//}