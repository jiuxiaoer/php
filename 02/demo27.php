<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/7 20:25
 */
// 先实现父子 进程间通信
$file = 'fifo_x';
if (!posix_access($file, POSIX_F_OK)) {
    // 创建一个 管道文件
    if (posix_mkfifo($file, 0666)) {
        print_r("created ok \n");
    }
}
$pid = pcntl_fork();
if ($pid == 0) {
    $fd = fopen($file, "r");
    $fread = fread($fd, 10);
    if ($fread) {
        print_r("recv" . $fread . "my pid " . getmypid() . "\n");
    }
    exit(0);
}
$fd = fopen($file, "w");
$fwrite = fwrite($fd, "hello", 5);
print_r("my pid " . getmypid() . "write  " . "hello" . "\n");
fclose($fd);

$pid=pcntl_wait($sattus);
if ($pid>0){
    print_r("exit ".$pid
    ."\n");
}