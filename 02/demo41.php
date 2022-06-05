<?php

$key=ftok('demo40.php','x');
$shm_id=shmop_open($key,"c",0666,128);
// 内存块 实际上是连续的存储空间
// 128
echo shmop_read($shm_id,0,5);
