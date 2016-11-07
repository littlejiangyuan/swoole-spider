<?php namespace Save;
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 01/11/16
 * Time: 18:57
 */

class MirrorSave {
    protected $file;
    protected $html;
    protected $url;

    public function __construct($url, $html) {
        $this->url  = $url;
        $this->file = $this->url->getFile();
        $this->file = $this->trueFile($this->file);
        $this->html = $html;

    }

    protected function trueFile($file) {
        if($file == '/' || $file == '' ) {
            $file = 'index.html';
        }

        return $file;
    }

    public function save() {
        $this->checkPath();

        $saveFile = \GlobalConf::$outputBasePath . $this->file;

        file_put_contents($saveFile, $this->html);

    }

    public function checkPath() {
        $pos = strrpos($this->file, '/');
        if($pos > 0) {
            $path = substr($this->file, 0, $pos);
        } else {
            $path = '';
        }

        $path = \GlobalConf::$outputBasePath . $this->url->getHost() .$path;

        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
    }

}