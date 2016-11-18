<?php

namespace Parse;
use Utils\Url;
use Utils\Fifo\Dispatch;

class HtmlParse {
    private $html;
    private $url;

    public function __construct($html, $url = null) {
        $this->html = $html;
        $this->url  = $url;
    }

    public function run() {
        $match = preg_match_all("/<[a|A].*?href=[\'\"]{0,1}([^>\'\"]*).*?>/", $this->html, $result);

        if($match){
            if(RUN_MODE == 1) {
                foreach ($result[1] as $url) {
                    $sub = substr($url, 0, 4);

                    if($sub != 'http') {
                        $url = $this->url->getProtocol() . '://' . $this->url->getHost() . $url;
                        $port = $this->url->getPort();
                    } else {
                        $char = substr($url, 5, 1);
                        if($char == 's') {
                            $port = 443;
                        } else {
                            $port = 80;
                        }

                    }
                    
                    $u = new Url($url, $this->url->getDepth(), $port);
                    Dispatch::put($u);
                }
            }
            return $result[1];
        }
        return [];
    }
}