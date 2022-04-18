<?php
//中断信号
// 信号是指 软件中断信号  软中断
// 中断信号处理程序 [信号处理函数 信号捕捉函数] 完以后 就会继续执行主程序

//中断源 就是产生中断信号的单元
//1) 在中断按键下产生的中断信号  ctrl+c ctrl+z ctrl+\
//2) 硬件异常产生的中断信号
//3) 在终端使用 kill 来发送中断信号
//4) posix_kill 函数 pcntl_alarm 函数 产生 中断信号
//5) 软件产生的中断信号 SIGURG[TCP/IP],SIGALRM

//中断响应
//对信号的处理就是中断响应
//1) 忽略
//2) 执行中断处理函数 [捕捉信号执行信号处理函数]
//3) 执行系统默认
// signal ====>动作[忽略，默认，执行用户编写好的信号处理函数]

//中断返回
//就是指中断服务程序运行完后返回的

//信号对进程的影响
//1)直接让进程终止
//2)直接让进程停止 SIGCONT 可以唤醒进程到前台继续运行
// SIGSTOP 让进程停止之后
// [1]+ Stopped   [1] 是 作业|工作 作业编号 job      jobs命令可以看到后台的进程
// ctrl+z 他会让进程丢到后台去停止 [背景] [前景]

//几个常用的中断信号
//
//SIGTSTP           交互停止信号，终端挂起键 ctrl+z 终端驱动产生此信号  [终端停止符] 终止+core
//SIGTERM          可以被捕捉，让程序先清理一些工作再终止。[终止]
//SIGSTOP          作业控制信号，也是停止一个进程，跟SIGTSTP 一样
//SIGQUIT           退出键 CTRL+\ 终端驱动程序产生此信号，同时产生core文件 [终端退出符]
//SIGINT              中断键 delete/ ctrl+c  [终端中断符]
//SIGCHLD          子进程终止时返回。
//SIGUSR1 ,SIGUSR2    用户自定义信号
//SIGKILL SIGSTOP       不能被 捕捉及忽略的,主要用于让进程可靠的终止和停止。


//pcntl
//pcntl_alrm  pcntl_signal pcntl_signal_dispatch   pcntl_sigpromask..
//jobs 作业控制
echo posix_getpid();
while (1){
    ;
}