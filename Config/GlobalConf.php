<?php

/**
 * Class GlobalConf全局配置文件
 */
class GlobalConf {
    public static $TableSize = 64000000;
    public static $startUrl = 'http://www.baidu.com';
    public static $basePath = '';

    public static function setBathPath() {
        self::$basePath = __DIR__ . '/../';
    }
}
