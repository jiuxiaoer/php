<?php


$fd=stream_socket_pair(AF_UNIX,SOCK_STREAM,0);
$fd[0];//用于读
$fd[1];//用于写
fwrite($fd[1],'hello',5);
echo  fread($fd[0],128);