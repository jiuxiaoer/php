<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/4/23 20:23
 */
// 守护进程
// 终端 进程组 会话 有密切联系
// 守护进程一半运行在后台 并且没有终端控制 同时守护进程 是一直运行的
// 进程启动之后 进程在内存的数据结构 会写入到 proc目录中

// 编写守护进程有以下几点要求
//1) 设置文件创建屏蔽字 umask(0)  屏蔽 linux 权限
//2) 一般父进程先fork 一个子进程 让后父进程退出 子进程 调用 setsid 函数来创建会话
// 如果调用 setsid 的进程是组长进程就会报错
//当调用 setsid 之后一般会创建 一个子进程 让会话首进程 退出 确保该进程不会再获得控制终端
// unix/linux 发行版本有BSD System v
// 进程调用 sid 之后 会变成 组长 进程 会话首进程 不再有控制终端
// 3)会把 根目录作为工作目录
// 4) 会把一些文件描述符关闭 标准输入 输出 错误 关掉
// fopen(/dev/null)  这是一个空设备文件 可以看做是一个黑洞文件  对改文件任何的读写数据 都会被丢弃
// 一般喜欢 吧/dev/null 代替 0,1,2
//echo “hello" write(1"hello")

//0
umask(0);

//1
$pid = pcntl_fork();
if ($pid>0){
    exit(0);
}
//2
// 该进程会变成组长进程，会话首进程，没有控制终端TTY ?
if (-1==posix_setsid()){
    fprintf(STDOUT,"setsid 失败\n");
}

$pid = pcntl_fork();
if ($pid>0){
    exit(0);//让会话首进程退出
}
//3 working directory
chdir("/");

//4 0 1 2 STDIN STDOUT STDERR
fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

//file_put_contents fopen fwrite fread
// 当关掉以上标准输入，标准输出，标准错误文件之后，如果后面要对文件的操作[比如创建一个文件，写文件等]
// 它返回的文件描述符就从0开始

// 这里用dev/null来代替标准输入，标准输出，标准错误[把键盘，显示器当作黑洞]
$stdin= fopen("/dev/null","a"); //0
$stdout = fopen("/dev/null","a"); //1
$stderr = fopen("/dev/null","a"); //2

echo "hello.x";//write(1,"hello.x")

//file_put_contents("/home/process/demo28.log","pid=".posix_getpid());
$fd = fopen("/www/test/02/demo28.log","a");//1

//fclose($fd);


//write(0,"pid=xx");
// web [nginx,apache] mysql
$pid = pcntl_fork();

if ($pid==0){

    fprintf($fd,"pid=%d,ppid=%d,sid=%d,time=%s\n",posix_getpid(),posix_getppid(),posix_getsid(posix_getpid()),time());
    while (1){
        sleep(1);
    }
    exit(0);
}

$pid = pcntl_wait($status);
if ($pid>0){

    fprintf($fd,"exit pid=%d,ppid=%d,sid=%d,time=%s\n",$pid,posix_getppid(),posix_getsid(posix_getpid()),time());
    fclose($fd);
    exit(0);

}







$pid=pcntl_fork();
if ($pid==0){
    printf($resource,'ppid=%d,sid=%d,pid==%d,time=%s \n',posix_getppid(),posix_getsid(getmypid()),getmypid(),time());
    exit(0);
}
$pcntl_wait = pcntl_wait($status);
if ($pid>0){
    printf($resource,'exit ppid=%d,sid=%d,pid==%d,time=%s \n',posix_getppid(),posix_getsid(getmypid()),$pcntl_wait,time());
    fclose($resource);
    exit(0);
}

