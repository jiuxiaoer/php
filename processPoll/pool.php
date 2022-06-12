<?php

namespace MsqServer;
/**
 * 进程池
 * 进程池: 主要是解决动态创建进程时的效率问题 ,因为动态创建进程的消耗很大
 * fork 一个进程,就要复制数据段+代码 (不同时间创建的进程 数据也可能不同)
 * 预先创建好一组进程[]
 * 中断信号|多进程|IPC消息队列|unix域套接字 socket_pair
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/6/11 17:43
 */
class Process
{
    public $_pid;
    public $_msgid;
}

class server
{
    public $_sockFile   = 'pool.sock';
    public $_processNum = 3;
    public $_keyFile    = 'pool.php';
    public $_idx;
    public $_proecss    = [];
    public $_sockfd;
    public $_run        = true;
    public $_roll       = 0;

    // 中断信号处理函数
    public function sigHandler($signo) {
        $this->_run = false;
    }

    public function __construct($num = 3) {
        $this->_processNum = $num;
        pcntl_signal(SIGINT, [$this, "sigHandler"]);
        $this->forkWorker();
        $this->Listen();
        // 回收子进程
        $exitPid = [];
        while (1) {
            $pid = pcntl_wait($status);//回收子进程
            if ($pid > 0) {
                $exitPid[] = $pid;
            }
            if (count($exitPid) == $this->_processNum) {
                break;
            }
        }
        // 删除系统消息队列
        foreach ($this->_proecss as $p) {
            msg_remove_queue($p->_msgid);
        }
        fprintf(STDOUT, "master shutdown\n");

    }

    //创建 worker 子进程 和消息队列
    public function forkWorker() {
        $processObj = new Process();
        for ($i = 0; $i < $this->_processNum; $i++) {
            $key                      = ftok($this->_keyFile, $i);// 根据 文件inode 和 i 来创建
            $msqid                    = msg_get_queue($key);      // 创建消息队列
            $process                  = clone $processObj;        // 克隆一个进程对象
            $process->_msgid          = $msqid;                   // 设置消息队列id
            $this->_proecss[$i]       = $process;                 // 把进程对象放master数组里
            $this->_idx               = $i;
            $this->_proecss[$i]->_pid = pcntl_fork();             // 创建子进程
            if ($this->_proecss[$i]->_pid == 0) {
                $this->worker();
            } else {
                continue;
            }
        }
    }

    //事件监听
    public function Listen() {
        $this->_sockfd = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!is_resource($this->_sockfd)) {
            print_r("socket create fail " . socket_strerror(socket_last_error()) . PHP_EOL);
        }
        unlink($this->_sockFile);
        if (!socket_bind($this->_sockfd, $this->_sockFile)) {
            print_r("socket bind fail " . socket_strerror(socket_last_error()) . PHP_EOL);
        }
        socket_listen($this->_sockfd, 10);
        $this->eventLoop();
    }

    //轮训选择 worker 进程去处理
    public function selectWorker($data) {
        /** @var  Process $process */
        $process = $this->_proecss[$this->_roll++ % $this->_processNum];
        $msqid   = $process->_msgid;
        if (msg_send($msqid, 1, $data, true, false)) {
            print_r("master send ok " . PHP_EOL);
        }
    }

    //事件循环
    public function eventLoop() {
        $readFds = [$this->_sockfd];
        $wrteFds = [];
        $exFds   = [];
        while ($this->_run) {
            pcntl_signal_dispatch();
            // select I/O 复用函数
            $ret = socket_select($readFds, $wrteFds, $exFds, NULL, NULL);
            if (FALSE === $ret) {
                break;
            } elseif ($ret === 0) {
                continue;
            }
            if ($readFds) {
                foreach ($readFds as $fd) {
                    if ($fd == $this->_sockfd) {
                        $connfd = socket_accept($fd);
                        $data   = socket_read($connfd, 1024);
                        if ($data) {
                            $this->selectWorker($data);
                        }
                        socket_write($connfd, 'ok', 2);
                        socket_close($connfd);
                    }
                }
            }
        }


        socket_close($this->_sockfd);
        foreach ($this->_proecss as $p) {
            if (msg_send($p->_msgid, 1, "quit")) {
                fprintf(STDOUT, "master send quit ok\n");
            }
        }
    }

    // worker 业务逻辑
    public function worker() {
        print_r("child start pid: " . getmypid() . socket_strerror(socket_last_error()) . PHP_EOL);
        /** @var  Process $process */
        $process = $this->_proecss[$this->_idx];
        $msqid   = $process->_msgid;
        while (1) {
            if (msg_receive($msqid, 0, $msgType, 1024, $msg)) {
                print_r("child start pid: " . getmypid() . " msg : {$msg} " . PHP_EOL);
                if (strncasecmp($msg, 'quit', 4) == 0) {
                    break;
                }
            }
        }
        print_r("child die pid: " . getmypid() . socket_strerror(socket_last_error()) . PHP_EOL);
        exit(0);
    }

}

(new server(3));