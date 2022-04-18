# exec 函数
exec 函数的功能 用来执行一个程序

pcntl 的进程拓展 pcntl_exec 它内部的系统调用函数 execve 函数

exec 一般的用法是 父进程先创建一个子进程 让后子进程 调用这个函数

正文段[代码段] + 数据段 会被新程序替换 他的一些属性会继承父进程 PID 不会发生变化

调用 php 解释器文件 执行 php 脚本 文本文件

pcntl_exec("/www/server/php/73/bin/php",['demo9_1.php',1,2,3],['test']);

指定解释器来运行

pcntl_exec('demo9_2',["a",'b','c']);

// #!/www/server/php/73/bin/php