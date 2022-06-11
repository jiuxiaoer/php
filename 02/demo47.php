<?php
/**
 * unix域 udp类型套字节进程间通信
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/6/11 17:05
 */
$file='unix_abc2';
$sockfd=socket_create(AF_UNIX,SOCK_DGRAM,0);
socket_bind($sockfd,$file);
// udb 是无连接的 不用监听
while (1){
    $len=socket_recvfrom($sockfd,$buf,1024,0,$unixClientFile);
    if ($len){
        print_r("recv data: {$buf} file: {$unixClientFile}");
        socket_sendto($sockfd,$buf,strlen($buf),0,$unixClientFile);
    }
    if(strncasecmp(trim($buf),"quit",4)==0){
        break;
    }
}
