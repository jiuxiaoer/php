<?php
/**
 * unix域 udp类型套字节进程间通信
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/6/11 17:05
 */
$file='unix_abc3';
$serverFile='unix_abc2';
$sockfd=socket_create(AF_UNIX,SOCK_DGRAM,0);
socket_bind($sockfd,$file); //为了发送回给通信的进程

$pid=pcntl_fork();
if ($pid==0){
    while (1){
        $len=socket_recvfrom($sockfd,$data,1024,0,$unixClientFile);
        if ($len){
            print_r("recv form server $data \n");
        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
    exit(0);
}
// udb 是无连接的 不用监听
while (1){
    $data = fread(STDIN, 128);
    if ($data){
        socket_sendto($sockfd,$data,strlen($data),0,$serverFile);
    }
    if(strncasecmp(trim($data),"quit",4)==0){
        break;
    }
}
$pid=pcntl_wait($status);
print_r("exit $pid  \n");