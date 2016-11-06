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

    public function __construct($file, $html) {
        $this->file = $this->trueFile($file);
        $this->html = $html;

    }

    protected function trueFile($file) {
        if($file == '/' || $file == '' ) {
            $file = 'index.html';
        }

        return $file;
    }

    public function save() {
        $saveFile = \GlobalConf::$outputBasePath . $this->file;

        file_put_contents($saveFile, $this->html);
    }


}