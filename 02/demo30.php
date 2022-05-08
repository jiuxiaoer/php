<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/7 21:37
 */
//该进程作为消息发送进程
// 非血缘 关系 进程间通信
$file = 'fifo_x';
if (!posix_access($file, POSIX_F_OK)) {
    // 创建一个 管道文件
    if (posix_mkfifo($file, 0666)) {
        print_r("created ok \n");
    }
}
$fd = fopen($file, "w");

while (1){
    $data=fgets(STDIN,128);
    if ($data){
        $pid=getmypid();
        $len=fwrite($fd,$data,strlen($data));
        print_r("write bytes $len  mypid = $pid \n");
    }
}
fclose($fd);
// 命令管道 可以用于任意进程间的通信