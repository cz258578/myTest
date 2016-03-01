<?php

namespace common\uitl;

use Yii;

/**
 * 工具类
 *
 * @version 1.0
 * @author weinengyu
 */
class UtilHelper
{

    public static function CurlGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //获取输出的文本流
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36');
        //重定向
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        //连接超时，这个数值如果设置太短可能导致数据请求不到就断开了
        //curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        //接收数据时超时设置，如果10秒内数据未接收完，直接退出
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        // UtilHelper::curl_redir_exec($curl);
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    /**
     * 根据地址获取经纬度
     * @param string $cityName 城市名称
     * @param double $longitude 详细地址
     */
    public static function BaiduMapGeo($cityName, $address)
    {

        $baiduApi = 'http://api.map.baidu.com/geocoder/v2/?ak=MI5nXHngvylYcLwm48WYhSPB&location&output=json&address=%s&city=%s';
        $url = sprintf($baiduApi, $address, $cityName);
        $resp = self::CurlGet($url);
        $json = @json_decode($resp, true);

        if ($json && isset($json['result']['location'])) {
            return $json['result']['location'];
        }
        Yii::error('百度API未获取到坐标城市名: ' . $url);
        return FALSE;
    }
    
    /**
     * 验证手机号
     * @param type $phone
     * @return boolean
     */
    public static function CheckMobile($phone)
    {
        if (!empty($phone) && preg_match("/^(13|15|17|18)\d{9}$/", $phone)) {
            return true;
        }
        return false;
    }

    /**
     * 验证邮箱
     * @param type $email
     * @return boolean
     */
    public static function CheckEmail($email)
    {
        $rule = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        if (!empty($email) && preg_match($rule, $email)) {
            return true;
        }
        return false;
    }

    /**
     * 格式化excel日期
     * @param type $email
     * @return boolean
     */
    public static function excelTime($date, $time = false) {

        if(function_exists('GregorianToJD')){

            if (is_numeric( $date )) {

            $jd = GregorianToJD( 1, 1, 1970 );

            $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );

            $date = explode( '/', $gregorian );

            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )

            ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )

            ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )

            . ($time ? " 00:00:00" : '');

            return $date_str;

            }

        } else {

            $date=$date>25568?$date+1:25569;

            /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/

            $ofs=(70 * 365 + 17+2) * 86400;

            $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');

        }
        return date('Y-m-d', strtotime('-1day', strtotime($date)));

        // return $date;
    }

    public static function CheckSmsCode($phone, $code)
    {
        $result = ['success' => true]; // 短信验证码正确

        // 配置项
        $api = 'https://webapi.sms.mob.com/sms/verify';

        $appkey = 'ea8543a75036';
         
        $postParmas = array(
            'appkey' => $appkey,
            'phone' => $phone,
            'zone' => '86',
            'code' => $code,
        );

   
        /* 发起一个post请求到指定接口 */
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api );
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $postParmas ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ));

        // 发送数据
        $response = curl_exec($ch);
        // 不要忘记释放资源
        curl_close( $ch );
        $responspObj = json_decode($response);

        if (! $responspObj) {
            $result['success'] = false;
            $result['constantCode'] = 'SMS_CODE_IS_FREQUENTLY';

            return $result;
        }

        switch ($responspObj->status) {
            case '405':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_APPKEY_IS_EMPTY';
                break;

            case '406':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_APPKEY_IS_INVALID';
                break;
            case '456':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_PHONE_IS_EMPTY';
                break;
            case '457':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_PHONE_IS_INVALID';
                break;
            case '466':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_CODE_IS_EMPTY';
                break;
            case '467':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_CODE_IS_FREQUENTLY';
                break;
            case '468':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_CODE_IS_ERROR';
                break;
            case '474':
                $result['success'] = false;
                $result['constantCode'] = 'SMS_NO_OPEN_SERVER_SWITCH';
                break;
            default:
                $result['success'] = true;
                break;
        }

        return $result;
    }
}
