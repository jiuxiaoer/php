<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/20 18:46
 */
// 进程控制

//程序被加载到内存 进程  分配信息 pid  ppid euid pgid gid egid
// pid
fprintf(STDOUT,"pid=%s\n",posix_getpid());
