<?php namespace Utils;

class Url {
    protected $url;
    protected $protocol;
    protected $host;
    protected $port;
    protected $file;
    protected $query;

    protected $depth;

    //解析url
    public function __construct( $url, $depth) {
        $this->url   = $url;
        $this->depth = $depth;

        $this->parseUrl();

    }

    private function parseUrl() {//可以过滤掉url中锚点
        $urlInfo = parse_url($this->url);

        $this->protocol = $urlInfo['scheme'];
        $this->host = $urlInfo['host'];
        $this->port = $urlInfo['port'] ? $urlInfo['port'] : 80;
        $this->file = $urlInfo['path'];
        $this->query = $urlInfo['query'];

        $this->url = $this->protocol . '://' . $this->host . ':' . $this->port . $this->file;
        
        if($this->query) {
            $this->url .= '?' . $this->query;
        }
        
    }

    public function hashCode() {
        $i = 0;
        $h = $this->port;
        while ($this->host[$i] != 0) {
            $h = 31*$h + $this->host[$i];
            $i++;
        }

        $i=0;
        while ($this->file[$i] != 0) {
            $h = 31*$h + $this->file[$i];
            $i++;
        }

        return $h % GlobalConf::$TableSize;
    }
    
    public function getUrl() {
        return $this->url;
    }

    public function getDepth() {
        return $this->depth;
    }
}

