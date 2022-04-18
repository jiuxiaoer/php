<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/2 19:02
 */
// 1) SUID,SGID
// set user Id, set group ID 设置用户ID 设置组ID
// 设置了 set user id  的程序就是特权程序  启动之后就是特权进程

//当特殊标志 ‘s’ 这个字符出现再文件拥有者的x权限位的时候 就叫 set UID, 简称 SUID 或者说叫 SUID 特权
//当特殊标志 ‘s’ 这个字符出现再文件拥有组的x权限位的时候 就叫 set UID, 简称 SUID 或者说叫 SUID 特权

/*
 *[root@VM-24-6-centos ~]# ls -al demo.c
-rw-r--r-- 1 root root 120 Mar 18 20:54 demo.c
[root@VM-24-6-centos ~]# ls -al /bin/passwd
-rwsr-xr-x 1 root root 27856 Apr  1  2020 /bin/passwd

 *
 */
// 2)SUID SGID 的用途
// 一般来说 以 root 用户启动的程序都是超级进程， 一般都是服务程序

// 有时候我们经常以普通用户来执行程序的 www
// 有时候普通进程需要访问一些特殊的资源， 这个时候我们就需要提升权限来访问

// linux etc/shadow 普通用户无法查看修改 删除 rw
// 但是 root 可以
// 普通用户[jack] 可以通过 /bin/passwd (SUID特权文件) 这个ELF可执行文件修改 /etc/shadow 文档
//3)如何设置SUID   就在可执行 文件的权限位 x 上设置 chmod u/g/o + s elf file   【chmod u+s 文件名】
$file = 'pwd.txt';
$uid = posix_getuid();
$euid = posix_geteuid();
print_r('uid:' . $uid . '  euid:' . $euid . "\n");
//这样设置不行
$posix_setuid = posix_setuid($euid);
$euid=posix_seteuid($euid);
//提权 [以便能访问特殊资源]
print_r('uid:' . $uid . '  euid:' . $euid . "\n");
if (posix_access($file, POSIX_W_OK)) {
    print_r("我能修改....\n");
    $fd=fopen($file,'a');
    fwrite($fd,'php is best!!!');
    fclose($fd);
} else {
    print_r("我不能修改....\n");
}
// 提权以后 一定要改回来