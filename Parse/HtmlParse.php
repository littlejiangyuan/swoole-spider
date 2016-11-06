<?php

namespace Parse;
use Utils\Url;

class HtmlParse {
    private $html;
    private $url;

    public function __construct($url, $html) {
        $this->html = $html;
        $this->url = $url;
    }

    public function run() {
        //var_dump($this->html);exit;
        $match = preg_match_all("/<[a|A].*?href=[\'\"]{0,1}([^>\'\"]*).*?>/",$this->html, $result);
        if($match){
            foreach($result[1] as $url) {
                $sub = substr($url, 0, 4);
                if($sub != 'http') {
                    $url = 'http://' . $url;
                }
                $u = new Url($url,$this->url->getDepth());
                \Config\GlobalVar::$urls->put($u);
            }
        }
    }
}