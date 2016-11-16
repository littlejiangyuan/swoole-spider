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
    public function __construct( $url, $depth, $port = 80) {
        $this->url   = $url;
        $this->depth = $depth;
        $this->port  = $port;
        
        $this->parseUrl();

    }

    private function parseUrl() {//可以过滤掉url中锚点
        $urlInfo = parse_url($this->url);

        $this->protocol = $urlInfo['scheme'] ? $urlInfo['scheme'] : 'http' ;
        $this->host = $urlInfo['host'];
        $this->port = $urlInfo['port'] ? $urlInfo['port'] : $this->port;
        if($this->protocol == 'https') {
            $this->port = 443;
        }
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

        while ($this->host[$i]) {
            $h = (int)(31*$h + ord($this->host[$i]));
            $i++;
        }

        $i=0;
        while ($this->file[$i]) {
            $h = (int)(31*$h + ord($this->file[$i]));
            $i++;
        }

        return $h % TABLE_SIZE;
    }
    
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getHost() {
        return $this->host;
    }
    
    public function getDepth() {
        return $this->depth;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function getProtocol() {
        return $this->protocol;
    }
}

