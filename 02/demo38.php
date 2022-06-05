<?php
// 父子血缘进程通信（共享内存）
$key=ftok('demo38.php','x');
 $shm_id=shm_attach($key,128);//1 bytes =8 bit

//$pid=pcntl_fork();
//if ($pid==0){
//    $data=shm_get_var($shm_id,1);
//    print_r("pid".getmypid()."read ".$data.PHP_EOL);
//    exit(0);
//}
shm_put_var($shm_id,1,'hello!');
//shm_remove($shm_id);
//pcntl_wait($status);

