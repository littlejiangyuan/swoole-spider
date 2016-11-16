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
use Utils\HashTable;

class GlobalVar {
    //public static $table; //hash map
    //public static $urls;
    //public static $firstUrl;
    
    public static function init() {
        global $table;
        global $todoUrls;
        global $firstUrl;

        $todoUrls = new FifoUrl();
        
        $url = new Url(\GlobalConf::$startUrl, 1);
        $todoUrls->put($url);
        $firstUrl = $url;
        
        //初始化hashtable
        $table = new HashTable();

    }
}