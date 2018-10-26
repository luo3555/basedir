<?php
header("content-type:text/html;charset=utf-8");
class Main {
    public function curl($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POST, false);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $this->printTime($info,$url, $response);
        return $response;
    }

    public function printTime($curlInfo,$url, $response) {
        $total_time = $curlInfo['total_time'];      //获得用秒表示的上一次传输总共的时间，包括DNS解析、TCP连接等。
        $namelookup_time = $curlInfo['namelookup_time'];      //获得用秒表示的从最开始到域名解析完毕的时间。
        $connect_time = $curlInfo['connect_time'];      //获得用秒表示的从最开始直到对远程主机（或代理）的连接完毕的时间。
        $pretransfer_time = $curlInfo['pretransfer_time'];      //获得用秒表示的从最开始直到文件刚刚开始传输的时间。
        $starttransfer_time = $curlInfo['starttransfer_time'];      //获得用秒表示的从最开始到第一个字节被curl收到的时间。
        $redirect_time = $curlInfo['redirect_time'];      //获得所有用秒表示的包含了所有重定向步骤的时间，包括DNS解析、连接、传输前（pretransfer)和在最后的一次传输开始之前。

        echo '访问地址L：'.$url."<br/>";
        echo "<p style='color:red;'>1. 总共的传输时间（total_time）为：" . $total_time . " 秒</p>";

        echo "2. 直到DNS解析完成时间（namelookup_time）为：" . $namelookup_time . " 秒<br/>";

        echo "3. 建立连接时间（connect_time）为：" . $connect_time . " 秒<br/>";

        echo "4. 传输前耗时（pretransfer_time）为：" . $pretransfer_time . " 秒<br/>";

        echo "5. 开始传输（starttransfer_time）为：" . $starttransfer_time . " 秒<br/>";

        echo "6. 重定向时间（redirect_time）为：" . $redirect_time . " 秒<br/><br/>";

        $json = json_decode($response, true);

        echo "7. API自身内部执行时间 (execution_time) 为:<span style='color:coral'>" . $json['execution_time'] . "</span> 秒<br/><br/>";

        echo "Response:<textarea>";
        print_r($json);
        echo '</textarea>';
    }
}


$itemSearchUrl = "http://api.onebound.cn/%s/api_call.php?key=qq15110089199&result_type=json&api_name=item_search&q=%s";
$itemUrl = "http://api.onebound.cn/%s/api_call.php?key=qq15110089199&result_type=json&api_name=item_get&num_iid=%s";

$types = array(
    'taobao',
    '1688',
    'jd'
);

echo '<h2>地址栏中输入参数格式?q=关键字</h2>';

$keywords = $_GET['q'];
if ($keywords) {
    $test = new Main();
    foreach ($types as $type) {
        $mtime = explode(' ', microtime());
        $startTime = $mtime[1] + $mtime[0];
        echo '<h2 style="color:red;">平台:' . $type . '</h2>';
        echo "开始搜索关键词:<b style='color:greenyellow;'>" . $keywords . "</b><hr/>";
        //echo "<p>开始时间：" . $startTime;

        $response = $test->curl(sprintf($itemSearchUrl, $type, $keywords));
        $mtime = explode(' ', microtime());
        $executionTime = round(($mtime[1] + $mtime[0]) - $startTime,5);

        $mtime = explode(' ', microtime());
        $executionTime = round(($mtime[1] + $mtime[0]) - $startTime,5);
        //echo '<p style="color:red;">搜索总时间(s):' . $executionTime;
        $result = json_decode($response, true);
        if (is_array($result)) {
            echo '<p style="color:blue;">开始查找产品具体信息:';
            $count = count($result['items']['item']);
            $numIid  = $result['items']['item'][rand(0,$count)]['num_iid'];
            echo $numIid . '</p><hr/>';
            $test->curl(sprintf($itemUrl, $type, $numIid));
        }
    }
    echo '<script>alert("完成");</script>';
}


//if(isset($_GET['url']) && !empty($_GET['url']))
//{
//    $test = new Main();
////    $test->curl($_GET['url']);
//    $url = "http://api.onebound.cn/taobao/api_call.php?key=qq15110089199&result_type=json&api_name=item_get&num_iid=567969460194";
//    //$url = 'https://www.baidu.com';
//    $test->curl($url);
//}else{
//    echo '请输入访问地址';
//}



?>
