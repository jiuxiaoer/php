<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/20 12:50
 */
// 解释器文件是一种文本文件
// 解释器可执行 ELF 文件
#!/usr/bin/php   php 文件加上 这行 指定为 php 解释器 可直接执行
// chmod 4|2|1
//bash 进程启动过程

// centos 登陆 再 login 服务启动 bash 进程
// 网络方式登陆 shell 终端 由 sshd 服务 去开启  监听 22端口

//进程观察命令
// pstree -ap

//ctrl +c 会产生中断信号 退出进程
//ctrl+z 功能把前台 进程 丢到 后台运行 暂停
//strace -f -s 65500 -o ssh1.log -p 1526 监听进程做了什么


