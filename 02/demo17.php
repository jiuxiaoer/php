<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/17 15:45
 */
//信号集  是指信号的集合
//主程序可以选择阻塞某些信号  被阻塞的信号集称为 阻塞信号集  信号屏蔽字 Block
//当进程阻塞了某些信号 通过 [pcntl_sigpromask来设置信号屏蔽集] 如果在运行期间 收到了
//阻塞的信号时 这个信号的处理程序不会被执行 这个信号会放在被挂起的信号集合里 信号未决集
//pcntl_sigpromask 来谁知 进程的信号屏蔽字

pcntl_signal(SIGINT,function ($sig){
    print_r("接收到了信号：".$sig." PID: ".getmypid()."\n");
});
// 设置信号屏蔽字
$sigset=[SIGINT,SIGUSR1];
pcntl_sigprocmask(SIG_BLOCK,$sigset);
$i=10;
while ($i--){
    pcntl_signal_dispatch();
    print_r("runging ..... pid=".getmypid()."\n")
    ;
    sleep(1);
    if ($i==5){
        //解除信号屏蔽
        print_r("已经解除\n");
        pcntl_sigprocmask(SIG_UNBLOCK,[SIGINT,SIGUSR1],$old);
        print_r($old);
    }
}