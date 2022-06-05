<?php
//unix 域套字节网络进程通信
//1)管道 posix_mkfifo  [它调用的系统函数是mkfifo]
// php 解释器 他在运行的时候, php 自己封装的函数 tcfr 是哪一个 strace 进行调试
// python 解释器 也是一样的
// 每个编程语言封装的函数可能不同 但是系统调用函数是一样的
// 2) system v ipc 消息队列 信号量 共享内存
// 3) 套接字 unix 域 套接字   [IPV4 IPV6 域通信]

// 套接字 socket 实际上是一种链接，套接字的通信域 类型有哪些 : ipv4【AF_INET】 ipv6【AF_INET6】
// UNIX[AF_UNIX] unix域  也叫本地域 [进程间通信的时候不需要通过网络（网卡）]
// ipv4 ipv6 通信要经过过网卡 数据到网卡 是一种 数据帧 ，【目标网络物理地址|源物理地址|...|数据】
// UNIX 通信是不经过网卡网络

// 套接字 类型 TCP UDP
//tcp: 需要连接 [三次握手] 是可靠的  重传  有序的 字节流服务
//udp: 不需要连接 不可靠 数据长度固定的 数据报服务
//raw
// unix 域 /ipv4 /ipv6 都支持tcp/udp

//ps: unix 域 的 udp是可靠的
// ipv4 ipv6 作为服务进程是需要绑定IP port 以便寻址
//port 用户确定进程间通信时 到底是哪个进程 IP 确实那台计算机  端口 确实那个进程 ！

//unix 套接字 作为命名unix域套接字时 一定要绑定地址[他的地址比较特殊 是一个文件 socket 文件]
//1)无命名的[c创建好的unix套接字不需要绑定地址bind]
//1)命名unix域套接字


//stream scoket [内部底层 还是 socketpair]

$fd =stream_socket_pair(AF_UNIX,SOCK_STREAM,0);
print_r($fd);