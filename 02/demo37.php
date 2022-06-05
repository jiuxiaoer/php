<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/18 19:55
 */
//共享内存  共享内存 读写 操作是最快的
// SystemV IPC 消息队列 信号量 共享存储
// 共享存储 实际上就是 系统会开开辟一块存储空间 进程会使用相关函数 shmget 来映射[连接]到进程的地址空间
//内存 内存分配出来的 是一块连续的存储空间 [在分配存储空间的时候 可以指定空间大小 一般按页分配]
// 对内存进行 读写操作 一定要 指定写入的位置
// 对内存的操作是非常复杂的 [c/c++ 分配 释放]
// cd /proc/sysvipc
$key=ftok('demo37.php','x');
echo $shm_id=shm_attach($key,128);//1 bytes =8 bit
//shmat 表示创建好的共享存储区域关联到 进程的地址空间
//shm_id 对应自己的地址空间 [当前进程] 实际上是映射了内存地址
echo shm_put_var($shm_id,1,"a");
echo shm_get_var($shm_id,1);
shm_remove($shm_id);