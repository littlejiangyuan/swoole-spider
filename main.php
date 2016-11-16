<?php

//定义运行模式，单进程还是多进程  1单进程2多进程
define("RUN_MODE", 2);

    //全局变量


    require_once __DIR__ . '/Config/GlobalConf.php';
    GlobalConf::setBathPath();
    require_once __DIR__ . '/autoload.php';



    /********************多进程模型********************/

    //创建Server对象，监听 127.0.0.1:9501端口
    $serv = new swoole_server("127.0.0.1", 9501);

    $serv->set(array(
        'worker_num' => 1,
        'task_worker_num' => 4
    ));

    $serv->on('Task', function ($serv, $task_id, $from_id, $data) {
        $len = (int)substr($data, 0, 6);
        $urlObj = substr($data, 6, $len);
        $urlObj = unserialize($urlObj);

        $obj = new Utils\Task($urlObj, substr($data, 6 + $len));

        $html = $obj->run();

        $serv->sendMessage($html, $from_id);
    });

    $serv->on('Finish', function($serv, $task_id, $data) {
        echo $data."\n";
    });

    //监听数据发送事件
    $serv->on('receive', function ($serv, $fd, $from_id, $data) {

    });

    //当工作进程收到由sendMessage发送的管道消息时会触发onPipeMessage事件。worker/task进程都可能会触发onPipeMessage事件
    $serv->on('PipeMessage',function (swoole_server $serv,  $from_worker_id, $message){
        $table    = null;
        $todoUrls = null;
        $firstUrl = null;
        $inLink   = true;
        $outputPath = null;
    });

    $serv->on('WorkerStart', function ($serv, $worker_id) {
        $table    = null;
        $todoUrls = null;
        $firstUrl = null;
        $inLink   = true;
        $outputPath = null;

        //初始化
        Config\GlobalVar::init();   

        if($worker_id < 1) {
            begin($serv);
        }
    });

    //启动服务器
    $serv->start();


    function begin($serv) {

        /*************同步io***************/
        while(1) {
            if(!$todoUrls->isEmpty()) {

                $url = $todoUrls->get();

                $html = file_get_contents($url);

                $urlObj = new \Utils\Url($url,1);
                $urlObjStr = serialize($urlObj);
                $len = strlen($urlObjStr);
                $len = str_pad((string)$len, 6, "0", STR_PAD_LEFT);

                $data = $len.$urlObjStr.$html;

                $serv->task($data);

            } else {
                sleep(1);
            }
        }


        /*********************异步io*********************/
        /*
        //添加的事件循环
        $fp = fsockopen('www.ifeng.com', 80, $errno, $errstr);
        stream_set_blocking($fp, 0); //设置非阻塞
        $str = '';
        $out = "GET / HTTP/1.1\r\n";
        $out .= "Host: www.ifeng.com\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);

        swoole_event_add($fp, function ($fp) use ($serv) {
            $str = '';
            while (!feof($fp)) {
                $str .= fgets($fp, 128);
            }

            $serv->task($str);
            //$urlObj = new \Utils\Url($url,1);
            //$obj = new Utils\Task($urlObj, $str);
            //$obj->run();

            swoole_event_del($fp); // socket处理完成后，从epoll事件中移除socket
        });
        */
    }







/*******************单进程模型********************/
/*
while(1) {
    if(!Config\GlobalVar::$urls->isEmpty()) {
        $url = Config\GlobalVar::$urls->get();
        echo $url."\n";
        $html = file_get_contents($url);
        $urlObj = new \Utils\Url($url,1);
        $obj = new Utils\Task($urlObj, $html);
        $obj->run();
        //echo Config\GlobalVar::$urls->count()."-";
    }
}

exit;
*/




