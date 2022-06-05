<?php

$key=ftok('demo40.php','x');
$shm_id=shmop_open($key,"c",0666,128);
// 内存块 实际上是连续的存储空间
// 128
echo shmop_write($shm_id,"hello",0);
echo shmop_delete($shm_id);