<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/8 14:49
 */
// System v IPC 消息队列 [消息队列,信号量,共享存储] posix IPC 没有封装
// 由系统内核维护的 一个队列  msgget 创建一个 消息队列 创建成功返回 一个队列的 ID
// 键key[对应内部数据结构的标识符ID]   ipcs 查看 系统的消息队列

// php 的函数 是封装 c 函数库 以及一些系统调用函数
// 1)strace
// 2)php-src 源码

$key=ftok("demo32.php","x");// 就是把文件与id 转换成一个key 根据inode
$msqid=msg_get_queue($key);
echo msg_receive($msqid,0,$msgType,1024,$msg);
var_dump($msgType);
echo $msg;