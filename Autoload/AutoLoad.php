<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 03/11/16
 * Time: 00:34
 */

class AutoLoad {

    public static function register($classname) {
        $filename = GlobalConf::$basePath . $classname.'.php';
        $filename = str_replace('\\', '/', $filename);
if($classname == 'Utils\FifoUrl') {
echo $filename;exit;
}
        if(file_exists($filename)) {
            require_once $filename;
        }
    }

}