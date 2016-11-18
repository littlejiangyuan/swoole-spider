<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/11/7
 * Time: 上午1:18
 */

namespace Utils;

use Save\MirrorSave;
use Parse\HtmlParse;

class Task {
    protected $url;
    protected $html;

    public function __construct($url, $html) {
        $this->url = $url;
        $this->html = $html;
    }

    public function run() {
        $save = new MirrorSave($this->url, $this->html);
        $save->save();

        if(RUN_MODE == 2) {
            $obj = new HtmlParse($this->html);
        } else {
            $obj = new HtmlParse($this->html, $this->url);
        }
        
        $urls = $obj->run();

        return $urls;
    }
}