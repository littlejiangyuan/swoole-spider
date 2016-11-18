<?php

    //hash表大小
    define("TABLE_SIZE", 64000000);

    //初始url
    define("START_URL", 'https://toutiao.io');

    //定义运行模式，单进程还是多进程  1单进程2多进程
    define("RUN_MODE", 1);

    //是否是io复用 0不是 1是
    define("MUTI_IO", 0);

    //全局变量
    $table    = null;  //hash表，存放url hash位图
    $todoUrls = null;  //待抓取url
    $firstUrl = null;  //初始url
    $inLink   = true;  //是否只抓取当前host的url
    $root     = null;
    $outputPath = null; //输出路径

    $root = __DIR__ . '/';
    $outputPath = $root . '/output/';

    require_once __DIR__ . '/autoload.php';
    $firstUrl = new \Utils\Url(START_URL, 1);


//初始化
$todoUrls = new \Utils\Fifo\FifoUrl();
$url = new \Utils\Url(START_URL, 1);
$todoUrls->put($url);
$firstUrl = $url;
//初始化hashtable
$table = new \Utils\HashTable();
while(1) {

    if(!$todoUrls->isEmpty()) {
        $url = $todoUrls->get();
        echo $url."\n";
        $html = file_get_contents($url);
        $urlObj = new \Utils\Url($url,1);
        $obj = new Utils\Task($urlObj, $html);
        $obj->run();

        echo $todoUrls>count()."-";
    }
}
exit;

    /********************多进程模型********************/

    //创建Server对象，监听 127.0.0.1:9501端口
    $serv = new swoole_server("127.0.0.1", 9501);

    $serv->set(array(
        'worker_num' => 2,
        'task_worker_num' => 4
    ));

    $serv->on('Task', function ($serv, $task_id, $from_id, $data) {
        global $inLink;
        global $firstUrl;

        $url = $data;
        $urlObj = new \Utils\Url($url, 1);
        echo $url."\n";

        $html = file_get_contents($url);


        if($html == false) {
            $serv->sendMessage('1', $from_id);
        }

        $obj = new \Utils\Task($urlObj, $html);

        $urls = $obj->run();

        foreach($urls as $key => $u) {
            if($inLink) {
                $pro = substr($u, 0, 4);
                if($pro != 'http') {
                    $host = $firstUrl->getHost();
                } else {
                    $urlInfo = parse_url($u);
                    $host = $urlInfo['host'];
                }

                if ($firstUrl->getHost() != $host) {
                    unset($urls[$key]);
                }
            }
        }

        if($urls) {
            $sendData = [
                'urlobj' => serialize($urlObj),
                'urls'   => $urls,
            ];
            $serv->sendMessage(json_encode($sendData), $from_id);
        } else {
            $serv->sendMessage('1', $from_id);
        }
    });

    $serv->on('Finish', function($serv, $task_id, $data) {
        echo $data;
    });

    //监听数据发送事件
    $serv->on('receive', function ($serv, $fd, $from_id, $data) {

    });

    //当工作进程收到由sendMessage发送的管道消息时会触发onPipeMessage事件。worker/task进程都可能会触发onPipeMessage事件
    $serv->on('pipeMessage',function (swoole_server $serv,  $from_worker_id, $message){
        global $todoUrls;

        if($message == 1) {

        } else {
            $message = json_decode($message, true);
            $urls = $message['urls'];
            $urlObj = unserialize($message['urlobj']);
            foreach ($urls as $url) {
                $sub = substr($url, 0, 4);
                if ($sub != 'http') {
                    $url = $urlObj->getProtocol() . '://' . $urlObj->getHost() . $url;
                    $port = $urlObj->getPort();
                } else {
                    $char = substr($url, 5, 1);
                    if ($char == 's') {
                        $port = 443;
                    } else {
                        $port = 80;
                    }

                }

                $u = new Utils\Url($url, $urlObj->getDepth(), $port);


                \Utils\Fifo\Dispatch::put($u);
            }
        }


        if(!$todoUrls->isEmpty()) {
            $url = $todoUrls->get();
            $serv->task($url);
        }



    });

    //2个work进程
    $serv->on('WorkerStart', function ($serv, $worker_id) {

        if($worker_id < 1) { //0号
            workerInit();
            begin($serv);
        } else {

        }
    });

    //启动服务器
    $serv->start();

    function workerInit() {
        global $table;
        global $todoUrls;
        global $firstUrl;

        //初始化
        $todoUrls = new \Utils\Fifo\FifoUrl();

        $url = new \Utils\Url(START_URL, 1);
        $todoUrls->put($url);
        $firstUrl = $url;

        //初始化hashtable
        $table = new \Utils\HashTable();
    }

    function begin($serv) {
        global $todoUrls;

        if(!$todoUrls->isEmpty()) {
            $url = $todoUrls->get();
            $serv->task($url);
        }



        /*************同步io***************/
        /*
        do {
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
                //sleep(1);
            }
        } while(1);
        */

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




