<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/6 0006
 * Time: 下午 5:06
 */

function showPID()
{
    $pid = posix_getpid();
    fprintf(STDOUT,"pid=%d,ppid=%d,pgid=%d,sid=%d\n",$pid,posix_getppid(),posix_getpgid($pid),posix_getsid($pid));

}
showPID();//ppid是和下面的2个子进程是不一样的
$pidMap=[];
$pid = pcntl_fork();//该子进程ppid的值是一样的，pgid也是一样的，sid也是一样的

if ($pid>0){
    $pidMap[$pid]=$pid;
    $pid = pcntl_fork();//该子进程ppid的值是一样的，pgid也是一样的，sid也是一样的
    if ($pid>0){
        $pidMap[$pid]=$pid;
    }else{
        $pid = posix_getpid();
        // 自己把自己设置为组长
        posix_setpgid($pid,$pid);

        $pid = pcntl_fork();
        if ($pid>0){
            $pidMap[$pid]=$pid;
        }

    }
}
// 孤儿进程：就是指父进程先 结束，但是子进程晚结束，这个时候子进程就是孤儿进程，它会被1号进程接管


showPID();

if ($pid>0){
    $i=0;
    while (1){
        $pid = pcntl_waitpid(-1,$status);
        if ($pid>0){
            $i++;
            echo "子进程 $pid 结束了\n";
        }
        unset($pidMap[$pid]);
        if (empty($pidMap)){
            break;
        }
    }

}