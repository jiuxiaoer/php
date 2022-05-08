<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/21 21:06
 */
// 进程组
//bash 进程拥有一个终端[输入和输出] 这种终端 我们一般也叫控制终端
//进程组 就是一个或者多个 进程的集合 一个进程 有个标识组 ID 表示该进程属于哪个进程组
// bash 进程启动之后 他会自己setsid把自己设置为会话首进程,也会设置自己为组长进程
// 同时他打开了 伪终端 从设备文件pts [他有一个伪终端驱动程序]
//bin/bash
// ---- php demo22.php  [tcp/ip linux 它有伪终端设备驱动程序 会模拟出一个终端]

var_dump("pid==",getmypid()."   ppid == ".posix_getppid()."  pgid== ".posix_getpgid(getmypid())."\n");

// 进程 正在执行的程序
// 这个进程肯定是在 bin/bash 进程[伪终端]里驱动的
// 先创建进程再执行程序
//启动之后 [execve 这个函数给我我们启动的] 他会继承 一些属性 比如说 组id 会话id 同时也会继承 父进程已经打开的文件描述符
//0 1 2 [伪终端的标准输入 标准输出 标准错误 pts,ptmx]