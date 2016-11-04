<?php 
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 03/11/16
 * Time: 19:25
 */

namespace Utils\Fifo;

use Utils\Url;

class Fifo {
    protected $in;
    protected $out;

    public $size = 100000; //总空间大小
    public $count;//使用空间大小

}

