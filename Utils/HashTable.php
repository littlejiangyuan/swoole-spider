<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/11/2
 * Time: 下午11:39
 */
namespace Utils;

class HashTable {
    private $table;
    
    /**
     * @param $create是否断点抓取
     */
    public function __construct($create = false) {
        if($create) {
            $this->init();
        } else { //读取临时文件

        }
    }
    
    private function init() {
        $total = GlobalConf::$TableSize/8;
        $char  = chr(0);
        
        for($i=0; $i < $total; $i++) {
            $this->table .= $char;
        }
        
    }
    

    public function set(Url $url) {
        $code = $url->hashCode();
        $position = $code/8;
        $bit = $code % 8;

        $this->table[$position] = $this->table[$position] || (1 << $bit);
    }

    public function isInBitMap(Url $url) {
        $code = $url->hashCode();
        $position = $code/8;
        $bit = $code % 8;
        $char = 1 << $bit; 
        
        return $this->table[$position] && $char;
    }
    
}