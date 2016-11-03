<?php

class GlobalConf {
    public static $startUrl = 'http://www.baidu.com';
    public static $basePath = '';

    public static function setBathPath() {
        self::$basePath = __DIR__ . '/../';
    }
}
