<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/5/8 14:49
 */


$key=ftok("demo34.php","x");
$msqid=msg_get_queue($key);
//当前消息队里的状态
print_r(msg_stat_queue($msqid));
$pid=pcntl_fork();
if($pid==0){

    while (1){
        //MSG_IPC_NOWAIT [启动非阻塞] 改系统调用函数将会立马返回
        // 使用阻塞非阻塞有什么区别 msgrcv 如果使用非阻塞方式 被调用的次数非常高 所以占用cpu 资源就高
        // 阻塞方式 调用次数就比较低 因为必须等消息队列有数据 才返回
       $res= msg_receive($msqid,1,$msgType,1024,
            $msg,true,MSG_IPC_NOWAIT,$error);

        if ($error!=MSG_ENOMSG){
            echo $msg."\r\n";
        }
       //       echo "\r\n";
    }
    exit(0);
}
$i=1;
while (1){
   msg_send($msqid,1,"hello",true,true);
   sleep(1);

   if ($i++ ==3){
       posix_kill($pid,SIGKILL);
       break;
   }
}
$pid = pcntl_wait($staus);
if ($pid>0){
    print_r($pid."已经回收");
}
if (msg_remove_queue($msqid)){
    echo "remove_ ok!!!";
}
