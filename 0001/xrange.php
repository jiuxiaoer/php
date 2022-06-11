<?php
// yield 协程中断 + 通信
function xrange() {
    for ($i = 0; $i <= 10; $i++) {

        $res=yield '函数内向外发';

        echo '协程   '.$i.$res.PHP_EOL;
    }
}
$a=xrange();
while ($a->valid()){
    $value= $a->send("向函数传递信息");
    echo $value;

    for ($j=1;$j<=1;$j++){
        echo '协程2   '.'aaaaaaaa'.PHP_EOL;
    }
}
