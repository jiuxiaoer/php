<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/22 21:06
 */
//进程退出
//一个进程启动之后   变成了一个进程   进程在以下情况下会退出
//1 运行到最后一行语句
//2 return 时间
//3 运行时遇到exit的时候
//4 程序异常的时候
//5 进程接受到中断信号

// 进程要么 正常结束 要么异常结束  [信号有关]
// 退出都有一个终止状态码 进程结束时 不会  真的退出 还会驻留 父进程可以使用 wait pcnt_wait 函数
// 来获取进程终止的状态码 同时该函数 会释放 终止进程的 内存空间 否则会容易造成僵尸进程 占用大量内存空间

//僵尸进程指 子进程已经结束 但是父进程还没使用 wait/wait_pid 回收
/**
 * root     28165 48.0  1.1 384292 21664 pts/0    R+   21:21   0:48 php -c /www/server/php/73/etc/php-cli.ini demo6.php
root     28166 48.9  0.2 384292  5068 pts/0    R+   21:21   0:48 php -c /www/server/php/73/etc/php-cli.ini demo6.php
 *
 *
 * ps -aux
 *   root     28732 88.1  1.1 384292 21656 pts/0    R+   21:24   0:09 php -c /www/server/php/73/etc/php-cli.ini demo6.php
     root     28733  0.0  0.0      0     0 pts/0    Z+   21:24   0:00 [php] <defunct>
 *
 *
 *                       进程动态生成的目录
 *   一个进程运行时 会生成 /proc/PID 这个目录文件
 *   如果开发一个守护进程的wab项目 如果开启了大量线程没有回收 服务器的内存和存储空间 会被挤满
 *   我们必须回收
 *
 * 进程的退出和回收
 *
 */
$pid=pcntl_fork();
if (0==$pid){
    echo '我是子进程运行完我就跑路  pid='.posix_getpid()."\n";
    while (1){
        ;
    }
exit(10); // 0成功  -1失败  最大值255
}else{
    echo "我是父进程pid=\n".posix_getpid();

    //$exitPid=pcntl_wait($status);
    $exitPid=pcntl_wait($status,WNOHANG);
//pcntl_wait 获取进程的状态码  和退出的进程标识id  还能释放子进程占用资源
    // 多进程开发一定要用这个函数来回收 子进程

    if ($exitPid>0){
        //pcntl_wexitstatus获取状态码
        echo '子进程挂了 并且已经完全释放'.$pid.'状态码  ：'.pcntl_wexitstatus($status)."\n";
    }else{
        echo "wait error..";
    }


    // 如果没有子进程 调用会错误
    // 子进程没有结束  pcntl_wait 会阻塞父进程
    // pcntl_wait  第三个选项option 可以让父进程 不阻塞
    //sleep(1);
while (1){
        print ("我在打印\n");
        sleep(3);
}

}
fprintf(STDOUT,"pid=%d",getmypid());