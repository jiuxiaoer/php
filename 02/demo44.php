<?php
// 用于血缘进程间的通信   全双工
// 用于本地域 通信
$fd=stream_socket_pair(AF_UNIX,SOCK_STREAM,0);
$fd[0];//用于读
$fd[1];//用于写
fwrite($fd[1],'hello',5);
$pid =pcntl_fork();
if ($pid==0){
    while (1){
        $data=fread($fd[0],128);
        if ($data){
            print_r("读取到的".$data."\n");
            fwrite($fd[1],'test',4);
        }
        if (strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
    exit();
}

// 发送数据
while (1){
    echo fread($fd[0],128);
    $data=fread(STDIN,128);
    if ($data){
        fwrite($fd[1],$data,strlen($data));

    }
    if (strncasecmp($data,"quit",4)==0){
        break;
    }
}


$pid=pcntl_wait($status);
print_r("exit $pid  \n");