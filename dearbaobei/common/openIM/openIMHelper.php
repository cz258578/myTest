<?php
    namespace common\openIM;

    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    // $httpdns = new HttpdnsGetRequest;
    // $client = new ClusterTopClient("appkey","appscret");
    // $client->gatewayUrl = "http://gw.api.taobao.com/router/rest";

    // var_dump($client->execute($httpdns));
    
    class openIMHelper extends \ClusterTopClient{

        /* 
        * 获取请求HTTP接口的对象
        * @param  openimRequest String 请求接口的HTTP类名称
        */
        public function getHtttpRequst($openimRequest = 'OpenimUsersAddRequest')
        {
            $requestObj = new $openimRequest();
            return $requestObj;
        }

        /* 
        * 重新构造执行函数
        * @param  openimRequest object 请求接口的HTTP对象
        */
        public function selfExecute($openimRequestObj) {
            return $this->execute($openimRequestObj);
        }
    }
?>