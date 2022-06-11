<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/6/9 16:44
 */
// 1) 创建 socket 文件
$file = 'unix_abc1';
// socket 文件描述符
$sockfd = socket_create(AF_UNIX, SOCK_STREAM, 0);
if (socket_connect($sockfd,$file)){
    print_r("connect ok \n");
    $pid=pcntl_fork();
    if ($pid==0){
        while (1){
            $len=socket_recv($sockfd,$data,1024,0);
            if ($len){
                print_r("recv form server $data \n");
            }
            if(strncasecmp(trim($data),"quit",4)==0){
                break;
            }
        }
        exit(0);
    }
    while (1){
        $data = fread(STDIN, 128);
        if ($data){
            socket_send($sockfd,$data,strlen($data),0);
        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
}
$pid=pcntl_wait($status);
print_r("exit $pid  \n");