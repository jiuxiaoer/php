<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/22 20:58
 */
// 就是一个进程组 或 多个进程组的集合
//1 一个会话 只要有一个控制终端 [物理终端/伪终端]
//2 一个绘画至少要有一个前台进程组 [前台就是指能输入的bin/bash] 其他就是后台进程组
//3 一个绘画如果链接了一个控制终端 就叫控制进程
// 因为这个绘画 首进程/bin/bash 是连接 控制终端的 [伪终端 设置驱动程序 + tcp/ip 对端的 SSHclient]
// 所以创建的 子进程 也会 继承 bin/bash 的控制终端
// 因为链接了终端 所以在终端输入会影响前台进程组 ctrl+c
function showPID() {
    $pid = posix_getpid();
    fprintf(STDOUT, "pid=%d,ppid=%d,pgid=%d,sid=%d\n", $pid, posix_getppid(), posix_getpgid($pid), posix_getsid($pid));
}

// 创建一个会话
//1 posix_setsid()
// 1) 不能使用组长进程 调用 setid
// 2) 我们一般先创建一个子进程 让父进程 退出 exit 由子进程调用 setsid
// 3) 调用setsid 之后 该进程 会变成 组长进程 同时也会变成会话首进程
// 4) 同时该进程没有控制终端  [他没有链接显示器键盘]
// 没有 控制终端 你再终端里输入任何数据都没有反应的
showPID();
$pid = pcntl_fork();
if ($pid > 0) {
    exit();

} else {
    // 该会话首进程 会断开 显示器和键盘 [虚拟控制终端]
    if (-1 == posix_setsid()) {
        echo 'error';
        $str = posix_errno();
        echo posix_strerror($str);
    } else {
        echo '会话创建成功';
    }
}
showPID();


while (1){
    sleep(2);
}