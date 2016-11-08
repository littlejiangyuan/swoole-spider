<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 07/11/16
 * Time: 04:21
 */
namespace Utils\Fifo;

use Config\GlobalVar;

class Dispatch {

    public static function put($u) {
        self::process($u);

        GlobalVar::$table->set($u);
    }

    private static function process($u) {
        if($u->getFile() == '/s/apk') {
            return;
        }
        //判断是否是内链，是否需要抓取
        if(\GlobalConf::$inLink) {
            if(GlobalVar::$firstUrl->getHost() !=  $u->getHost()) {
                return;
            }
        }

        //首先判断是否已经抓取过
        if(GlobalVar::$table->isInBitMap($u)) {
            return ;
        }


        //先测试内存是否可以存储
        if(GlobalVar::$urls->put($u)) {
            return;
        }

        //最后存储到磁盘
    }

    public static function get() {
        
    }
}