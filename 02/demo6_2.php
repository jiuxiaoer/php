<?php
/**
 * Auther: yinshen
 * url:https://www.79xj.cn
 * 创建时间：2022/3/23 19:44
 */
echo posix_getpid();
echo pcntl_wait($status);
$err=pcntl_errno();
printr(pcntl_strerror($err));