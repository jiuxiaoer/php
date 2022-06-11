<?php
// 命名unix域 [本地域] 套接字 [tcp udp]
// 在创建好 unix域 套接字的时候一定要绑定地址 地址是一个文件
// 1) 创建 socket 文件
$file = 'unix_abc1';

// socket 文件描述符
$sockfd = socket_create(AF_UNIX, SOCK_STREAM, 0);
// 2)
socket_bind($sockfd, $file);
// 3)
socket_listen($sockfd, 5); // 监听队列的大小
//4) 接收 IO 复用
$connfd = socket_accept($sockfd);

if ($connfd) {
    while (1) {
        $data = socket_read($connfd,1024);
        if ($data) {
            print_r("recv form client $data \n");
            socket_write($connfd,$data,strlen($data));
        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
}
socket_close($connfd);
socket_close($sockfd);


