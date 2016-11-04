<?php 
/**
 * Created by PhpStorm.全局变量
 * User: yuan
 * Date: 16/11/4
 * Time: 上午12:16
 */
namespace Config;
use Utils\Fifo\FifoUrl;
use Utils\Url;


class GlobalVar {
    public static $table; //hash map
    
    public static $urls;
    
    public static function init() {
        self::$urls = new FifoUrl();
        
        $url = new Url(\GlobalConf::$startUrl, 1);
        self::$urls->put($url);
    }
}