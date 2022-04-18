<?php

$pid=pcntl_fork();
if (0==$pid){
    echo '我是子进程运行完我就跑路  pid='.posix_getpid()."\n";
    exit(10); // 0成功  -1失败  最大值255
}



    while (1){
        echo "我是父进程pid=".posix_getpid()."\n";

// wait 以未阻塞方式运行
// 如果有子进程退出  就会返回 退出的子进程  如果没有 wait 就是0
        $exitPid=pcntl_wait($status,WNOHANG);


        if ($exitPid>0){
            //pcntl_wexitstatus获取状态码
            echo '子进程挂了 并且已经完全释放'.$pid.'状态码  ：'.pcntl_wexitstatus($status)."\n";
            break;
        }
        elseif ($exitPid==0){
            print ("我在打印1\n");
        } else{
            echo "wait error..";
        }

        print ("我在打印2\n");

    }

fprintf(STDOUT,"pid=%d",getmypid());