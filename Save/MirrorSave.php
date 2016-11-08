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

        $this->html = $html;

    }

    protected function trueFile($file) {
        if(strpos($file, '.')) {
            $file = 'index.html';
        }
        if($file == '/' || $file == '' ) {
            $file = 'index.html';
        }


        return $file;
    }

    public function save() {
        $saveFile = $this->checkPath();

        file_put_contents($saveFile, $this->html);
    }

    public function checkPath() {
        $pos = strrpos($this->file, '/');
        $trueFile = '';
        if($pos >= 0) {
            $path = substr($this->file, 0, $pos);
            $trueFile = substr($this->file, $pos + 1);
        } else {
            $path = '';
        }

        if(strpos($trueFile, '.') === false) {
            $path = $trueFile;
            $trueFile = 'index.html';
        }

        $path = \GlobalConf::$outputBasePath . $this->url->getHost() . '/'. $path;

        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if($trueFile == '') {
            $trueFile = 'index.html';
        }

        return $path . '/' . $trueFile;
        
    }

}