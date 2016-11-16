<?php

namespace Parse;
use Utils\Url;
use Utils\Fifo\Dispatch;

class HtmlParse {
    private $html;
    private $url;

    public function __construct() {

    }

    public function run() {
        $match = preg_match_all("/<[a|A].*?href=[\'\"]{0,1}([^>\'\"]*).*?>/", $this->html, $result);

        if($match){
            return $result[1];
            /*
            foreach($result[1] as $url) {
                $sub = substr($url, 0, 4);
                if($sub != 'http') {
                    $url = $this->url->getProtocol() . '://' . $this->url->getHost() . $url;
                }

                $u = new Url($url,$this->url->getDepth(), $this->url->getPort() );
                Dispatch::put($u);
            }
            */
        }
        return [];
    }
}