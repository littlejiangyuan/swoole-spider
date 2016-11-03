<?php namespace Utils;

class Url {
    protected $url;

    protected $protocol;
    protected $host;
    protected $port;
    protected $dir;
    protected $file;

    protected $depth;

    //解析url
    public function __construct( $url, $depth) {
        $this->url   = $url;
        $this->depth = $depth;

        $this->parseUrl();

    }

    private function parseUrl() {
        $urlInfo = parse_url($this->url);

        $this->protocol = $urlInfo['scheme'];
        $this->host = $urlInfo['host'];
        $this->port = $urlInfo[''];
        $this->dir = $urlInfo['path'];
        $this->file = $urlInfo[''];
    }

    public function hashCode() {

    }

    
}

