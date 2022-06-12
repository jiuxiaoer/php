<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/6/12 14:12
 */
$_sockFile = 'pool.sock';
$sockfd    = socket_create(AF_UNIX, SOCK_STREAM, 0);
if (socket_connect($sockfd, $_sockFile)) {
    echo socket_write($sockfd, 'hello', 5);
    echo socket_read($sockfd, 1024);
}
socket_close($sockfd);