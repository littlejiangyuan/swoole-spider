<?php

/**
 * Class GlobalConf全局配置文件
 */
class GlobalConf {
    public static $TableSize = 64000000;
    public static $startUrl = 'http://www.baidu.com';
    public static $root = '';
    public static $outputBasePath = '';

    public static function setBathPath() {
        self::$root = __DIR__ . '/../';
        self::$outputBasePath = self::$root . '/output/';
    }
}
