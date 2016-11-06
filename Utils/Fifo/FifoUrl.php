<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 04/11/16
 * Time: 03:22
 */

namespace Utils\Fifo;

use Utils\Fifo\Fifo;
use Utils\Url;

/**
 * Class FifoUrl
 * @package Utils 待抓取的url文件 存储在磁盘
 */
class FifoUrl extends Fifo {
    protected  $buffer = null;

    public function __construct($size = 0) {
        $this->in  = 0;
        $this->out = 0;

        if($size) {
            $this->size = $size;
        }

        $this->buffer = [];
    }

    public function put(Url $url) {
        if($this->isFUll()) {
            return false;
        }

        $urlStr = $url->getUrl();
        $this->buffer[$this->in] = $urlStr;

        $this->in = ( $this->in + 1 ) % $this->size;
        $this->count++;
    }

    public function get() {
        if($this->isEmpty()) {
            return false;
        }

        $url = $this->buffer[$this->out];
        $this->out = ( $this->out + 1 ) % $this->size;
        $this->count--;
        return $url;
    }

    public function isEmpty() {
        if($this->in == $this->out) {
            return true;
        }

        return false;
    }

    public function isFUll() {
        if(($this->in + 1) % $this->size == $this->out) {
            return true;
        }

        return false;
    }

    public function count() {
        return $this->count;
    }

}