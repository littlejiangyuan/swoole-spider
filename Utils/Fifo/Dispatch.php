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
        global $table;
        self::process($u);

        $table->set($u);
    }

    private static function process($u) {
        global $table;
        global $inLink;
        global $firstUrl;
        global $todoUrls;

        if($u->getFile() == '/s/apk') {
            return;
        }
        //判断是否是内链，是否需要抓取
        if($inLink) {
            if($firstUrl->getHost() !=  $u->getHost()) {
                return;
            }
        }

        //首先判断是否已经抓取过
        if($table->isInBitMap($u)) {
            return ;
        }


        //先测试内存是否可以存储
        if($todoUrls->put($u)) {
            return;
        }

        //最后存储到磁盘
    }

    public static function get() {
        
    }
}