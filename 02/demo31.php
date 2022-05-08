<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/7 21:37
 */
//该进程作为接受消息进程
// 非血缘 关系 进程间通信
$file = 'fifo_x';
if (!posix_access($file, POSIX_F_OK)) {
    // 创建一个 管道文件
    if (posix_mkfifo($file, 0666)) {
        print_r("created ok \n");
    }
}
$fd = fopen($file, "r");
stream_set_blocking($fd,0);
while (1){
    $data=fread($fd,128);
    if ($data){
        $pid=getmypid();
        print_r("recv $data mypid = $pid \n");
    }
}
fclose($fd);