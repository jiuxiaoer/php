<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/7 20:25
 */
// 读端 关闭的时候 写段还在 写  就无法写了 会产生中断信号
// 先实现父子 进程间通信
$file = 'fifo_x';
if (!posix_access($file, POSIX_F_OK)) {
    // 创建一个 管道文件
    if (posix_mkfifo($file, 0666)) {
        print_r("created ok \n");
    }
}
pcntl_signal(SIGPIPE,function ($signo){
    print_r("signo   $signo\n");
});
$pid = pcntl_fork();
if ($pid == 0) {
    $fd = fopen($file, "r");
    stream_set_blocking($fd,0); // 将文件 设置为 非阻塞方式
    $i=0;
    while (1){
        $fread = fread($fd, 10);  // 不管 有没有数据 写段 没有数据  不管  函数 立马返回
        // 只要调用 read 函数 不会阻塞 挂起 会立马返回
        // 阻塞的话会挂起 并不会立马返回
        if ($fread) {
            $i++;
            print_r("recv  " . $fread . "my pid " . getmypid() . "\n");
            if ($i==5){
                fclose($fd);
                break;
            }

        }
    }
    var_dump($i);
    exit(0);
}
$fd = fopen($file, "w");
stream_set_blocking($fd,0);
while (1){
    pcntl_signal_dispatch();
    $fwrite = fwrite($fd, "hello", 5); // 如果 有缓存 空间 就能写进去
    print_r("my pid " . getmypid() . "write  " . "hello" . $fwrite."\n");
    pcntl_signal_dispatch();
    $pid=pcntl_wait($status,WNOHANG);
    if ($pid>0){
        print_r("exit ".$pid
            ."\n");
        break;
    }
}
fclose($fd);

//$pid=pcntl_wait($sattus);
//if ($pid>0){
//    print_r("exit ".$pid
//        ."\n");
//}

// 读端 已经关闭 写段还在 写数据 是无法继续写的 还会产生 中断 SIGPIPE 信号