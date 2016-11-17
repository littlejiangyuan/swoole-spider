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

        $obj = new HtmlParse($this->html);
        $urls = $obj->run();

        return $urls;
    }
}