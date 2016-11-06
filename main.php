<?php

    require_once __DIR__ . '/Config/GlobalConf.php';
    GlobalConf::setBathPath();

    require_once __DIR__ . '/autoload.php';

    //初始化
    Config\GlobalVar::init();    


    while(1) {
        if(!Config\GlobalVar::$urls->isEmpty()) {
            $url = Config\GlobalVar::$urls->get();
            $html = file_get_contents($url);
            $obj = new Utils\Task($url, $html);
            $obj->run();
            echo Config\GlobalVar::$urls->count()."-";
        }
    }


    exit;



    //创建Server对象，监听 127.0.0.1:9501端口
    $serv = new swoole_server("127.0.0.1", 9501);

    $serv->set(array(
        'worker_num' => 4,
    ));

    //监听连接进入事件
    $serv->on('connect', function ($serv, $fd) {
        echo "Client: Connect.\n";
    });

    //监听数据发送事件
    $serv->on('receive', function ($serv, $fd, $from_id, $data) {
        echo $fd.'-'.$from_id;
        //echo $data;
    });

    //work进程启动
    $serv->on('WorkerStart', function ($serv, $worker_id){

        if($worker_id != 1) {
            return;
        }

        $hostname = 'www.baidu.com';
        $port = '80';
        $fp = fsockopen($hostname,$port,$errno,$errstr);
        stream_set_blocking($fp,0); //设置费阻塞

        //添加的事件循环
        swoole_event_add($fp);
        /*
        swoole_event_add($fp, function($fp) {
            while (!feof($fp)) {
                //echo fgets($fp, 128);
            }

            swoole_event_del($fp); // socket处理完成后，从epoll事件中移除socket
        });
*/

        if(!$fp)  {
            echo "$errno : $errstr<br/>";
        } else {
            $out = "GET / HTTP/1.1\r\n";
            $out .= "Host: www.baidu.com\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);

            //fclose($fp);
        }


    });

    //监听连接关闭事件
    $serv->on('close', function ($serv, $fd) {
        echo "Client: Close.\n";
    });

    $serv->on('start', function ($serv) {
        echo "start---\n";
    });

    //启动服务器
    $serv->start();