<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/28 19:58
 */
// 在 bin/bash 进程下启动的命令 一般称为工作|作业
// 前景|也叫前台 一般受 ctrl+c 等指令的影响
// 后台 不受 ctrl+c 等影响
// fg/bg
//1) & 可以吧作业丢到背景中执行
//2) jobs 可以列举出 背景中的作业 -l 所有的  -r 运行的 -s 停止的  fg把停止的作业放到前景运行
//3) ctrl+z 可以把进程放到背景中停止    // bg 运行停止的作业


// 作业会随着 bin/bash 退出
//1) 守护进程
//2) nobup php  xxx.php & 就可以让进程 与控制终端断开 成为守护进程
echo posix_getpid();
while (1){
    ;
}