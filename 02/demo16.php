<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/17 13:40
 */
// 中断信号处理
//$signo 信号名字
//$handler 中断信号处理程序
//$restart_syscall = true 是否要重启被中断的应用


//中断系统调用
//当系统正在执行系统调用的时候 接收到中断信号 那么这个系统调用就会被中断
//比如进程正在写文件
//无法恢复

//如果能恢复 我们成为 “可重入函数” 否则就是非可重入函数
//一般来说phper不需要关注 由 php 解释器 封闭实现
// 系统调用函数会返回-1 errno 会设置为 EINTR 中断错误 在编写网络编程的时候

// 信号捕捉 自定义信号处理程序
// 每个信号都有响应的 动作 处理程序
//1)用户自定义的中断信号处理程序 捕捉
//2)SIG_DEF 系统默认动作  [一般都是进程终止或者停止]
//3)SIG_IGN 忽略
//SIGKILL SIGSTOP 不可以捕捉或者忽略  主要用于可靠的 终止 停止 进程
// 进程启动的时候 信号的动作默认是系统行为 系统默认动作
// 再编写信号处理的时候 会覆盖默认的系统动作
function sigDump($signo){
    print_r("我接受到一个编号：".$signo." PID: ".getmypid()."\n");
}
pcntl_signal(SIGINT,'sigDump');
pcntl_signal(SIGUSR1,'sigDump');
pcntl_signal(SIGUSR2,SIG_IGN);
//该信号无法捕捉
//pcntl_signal(SIGKILL,function ($signo){
//    print_r("我接受到一个编号：".$signo."\n");
//});
//pcntl_signal(SIGSTOP,function ($signo){
//    print_r("我接受到一个编号：".$signo."\n");
//});
//每个信号都有默认动作 可能是 停止 终止 产生core文件
// 一般中断信号处理函数不要写太多业务逻辑
// 一般来说我们经常把中断信号用于通知
// 父进程创建 子进程的时候 子进程会继承父进程的 信号处理
$pcntl_fork = pcntl_fork();
if ($pcntl_fork==0){
    //子进程重置信号处理程序
    pcntl_signal(SIGUSR1,function ($sig){
        print_r("子进程：".$sig." PID: ".getmypid()."\n");
    });
}
while (1){
    pcntl_signal_dispatch();
    print_r("mian 再做一些事情 PID:".posix_getpid()."\n");
    sleep(3);
}