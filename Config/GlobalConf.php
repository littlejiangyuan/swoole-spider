<?php

/**
 * Class GlobalConf全局配置文件
 */
class GlobalConf {
    public static $TableSize = 64000000;
    public static $startUrl = 'https://toutiao.io/';
    public static $root = '';
    public static $outputBasePath = '';
    public static $inLink = true; //设置为true表示只抓取同host的

    public static function setBathPath() {
        self::$root = __DIR__ . '/../';
        self::$outputBasePath = self::$root . '/output/';
    }
}
