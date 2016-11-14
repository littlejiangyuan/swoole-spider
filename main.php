<?php

    require_once __DIR__ . '/Config/GlobalConf.php';
    GlobalConf::setBathPath();
    require_once __DIR__ . '/autoload.php';
    //初始化
    Config\GlobalVar::init();

    /********************多进程模型********************/

    //创建Server对象，监听 127.0.0.1:9501端口
    $serv = new swoole_server("127.0.0.1", 9501);

    $serv->set(array(
        'worker_num' => 1,
        'task_worker_num' => 4
    ));

    $serv->on('Task', function ($serv, $task_id, $from_id, $data) {
        return $data;
    });

    $serv->on('Finish', function($serv, $task_id, $data) {
        echo $data;
    });

    //监听数据发送事件
    $serv->on('receive', function ($serv, $fd, $from_id, $data) {

    });

$serv->on('start', function ($serv) use ($serv) {
    begin($serv);
});

//启动服务器
$serv->start();


    function begin($serv) {
        //添加的事件循环
        //$url = 'http://www.baidu.com';
        //$urlObj = new \Utils\Url($url, 1);

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

            //$obj = new Utils\Task($urlObj, $str);
            //$obj->run();

            swoole_event_del($fp); // socket处理完成后，从epoll事件中移除socket
        });

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




