<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/30 20:45
 */
//多进程编程
//一
//pcntl_fork 函数来创建一个子进程  系统调用是使用clone 来创建的
// 1 到底创建了多少个进程  1--2  2
// 2 每个进程$count 是多少
// 3 每个进程是从哪个地方开始 运行代码的
// 4 fork 之后 每个进程的变量 $i $count 的值到底是多少
// 5 每个进程运行到哪一行语句结束

//分析
// 1) php demo1 开始运行
// 2)
// step1 :
// 遇到 fork 函数后创建了一个子进程 这个子进程命名为 child1  [$i =0; $count =10]
// child1 继续执行 满足 pid=0 count+1==11  $i++ $i=1;
// step2 :
// cpu 运行调度到 parent process  要运行 $count=100,$i=1;
// step3 :
// cpu 还在运行 parent process fork 后又产生了一个子进程 child2 [$i=1,$count=100]
// cpu 还在调度 parent process $count=1000; $i=2 for循环退出
// 父进程的最终结果 $count =1000

// cpu 运行 child2 子进程
// 执行$count++ $count=101 $=2;
// child2 子进程的最终结果为 $count=101

// cpu 又调度到 child1 子进程 [$i=1,$count=11]
// 这个时候 child1 执行 fork 产生的子进程 命名为 child3 [$i=1;$count=11]
// child1 继续执行 else $count=110,$i=2
// child1 的最终结果是 $count=110

// cpu 又调度到 child3 子进程
// $count=12

// child1 and child2 是兄弟进程
// child1 and child3 是父子进程

//二 我加上break 之后,到底有几个子进程,每个进程的count值又是多少
//1 cpu 调度主进程执行 fork 产生了一个子进程 命名为 child1 这个时候 [$i=0 $count=10;]
//  cpu 继续执行主进程 $count=100 $i=0; 这个时候退出 fork 循环
// 主进程最终结果 $count=100;
//2 cpu调度到 child1 子进程 执行if分支 $count=11;$i=1;
// 继续运行该进程执行 fork 又创建了一个进程 child2 [$count=11,$i=1]
// cpu还是继续调度 child1 子进程 执行 else 分支 $count=110 child1 遇到 break 退出
// child1 的最终结果 $count=110
//3 cup 调度到 child2 子进程 执行 if $count=12 $i=2;
// child2 的最终结果 $count=12;

// child1 的父亲是主进程
// child2 的父亲是 child1
$count=10;
for($i=0;$i<2;$i++){
    $pid=pcntl_fork();
    if ($pid==0){
        $count++;
    }else{
        $count*=10;
        break;
    }
};
while (1){
    echo("pid==".posix_getpid()."   ".$count."\n");
    sleep(3);
}
