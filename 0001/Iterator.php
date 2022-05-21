<?php

/**
 * php 迭代器
 */
class myIterator implements Iterator {

    private $position = 0;
    private $array = array(
        "firstelement",
        "secondelement",
        "lastelement",
    );

    public function __construct() {
        $this->position = 0;
    }
    //返回到迭代器的第一个元素
    public function rewind() {
        var_dump(__METHOD__);
        $this->position = 0;
    }
    //返回当前元素
    public function current() {
        var_dump(__METHOD__);
        return $this->array[$this->position];
    }
    //返回当前元素的键
    public function key() {
        var_dump(__METHOD__);
        return $this->position;
    }
    //向前移动到下一个元素
    public function next() {
        var_dump(__METHOD__);
        ++$this->position;
    }
    //检查当前位置是否有效
    public function valid() {
        var_dump(__METHOD__);
        return isset($this->array[$this->position]);
    }
}

$it = new myIterator;

foreach($it as $key => $value) {
    var_dump($key, $value);
    echo "\n";

}
?>