<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 02/11/16
 * Time: 00:16
 */
use Utils\Url;

class Hashtable {
    protected $table;

    public function __construct() {
        $this->table = '';
    }

    /**
     * 支持断点爬虫，保存到临时文件
     */
    public function save() {

    }

    public function set(Url $url) {
        $code = $url->hashCode();
        $position = $code/8;
        $bit = $code % 8;

        $this->table[$position] = $this->table[$position] || (1 << $bit);
    }
}