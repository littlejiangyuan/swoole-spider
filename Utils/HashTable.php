<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/11/2
 * Time: 下午11:39
 */

class HashTable {
    private $table;
    /**
     * @param $create是否断点抓取
     */
    public function __construct($create = false) {
        $total = GlobalConf::$TableSize/8;

        if($create) {
            $this->table = "";
        } else { //读取临时文件

        }
    }

    public function save() {

    }

    public function setUrl() {

    }

    public function test() {

    }

    public function setTest() {
        
    }
}