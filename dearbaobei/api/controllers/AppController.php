<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use common\uitl\ErrorHelper;

/**   
*
* api 接口控制器
* 
* @author weinengyu   
* 
*/
class AppController extends Controller
{
    public static $request;

    /**
     * 项目初始化要运行的动作
     */
    public function init()
    {
        parent::init();
        //your code here
        self::$request = Yii::$app->request;
        $this->enableCsrfValidation = false;
    }

    /**
     * 获取页面传参
     * @param type $key
     * @param type $is_need
     * @param type $default_value
     * @return type
     */
    public function getParam($key, $is_need = true, $default_value = NULL)
    {
        $val = self::$request->get($key);
        if ($val === NULL)
        {
            $val = self::$request->post($key);
        }
        if ($is_need && $val === NULL)
        {
            $this->Error(\common\uitl\ErrorHelper::GLOBAL_INVALID_PARAM, 'required param: ' . $key);
        }
        return $val !== NULL ? $val : $default_value;
    }

    /**
     * 成功返回
     * @param array $_data
     */
    public function Success($_data = false)
    {
        $_msg = [
            'ok' => true,
            'serverTime' => time(),
            ];
        if (is_array($_data))
        {
            $_msg += $_data;
        }
        $this->Json($_msg);
    }

    /**
     * 错误返回
     * @param integer $_errID
     */
    public function Error($_errID = '10000', $ext_msg = null)
    {
        $_msg = [
            'ok' => false,
            'serverTime' => time(),
            'errorId' => $_errID,
            'errorMsg' => \common\uitl\ErrorHelper::getMsg($_errID) . ($ext_msg ? ','.$ext_msg : ''),
            ];
        $this->Json($_msg);
    }

    /**
     * JSON输出并结束
     * @param array $_arr
     */
    public function Json($_arr)
    {
        header('Content-Type:application/json; charset=utf-8;');
        echo(json_encode($_arr));
        Yii::$app->end();
    }

    /**
    * 校验通行证
    * @param isNeed bool 是否是必须的
    */
    public function checkUserToken($isNeed = true)
    {
        $tokenUserId = (int)$this->getDecodeToken($this->getParam('token', false)); // 通行证
        if ($tokenUserId < 1 && $isNeed) {
            $this->Error(ErrorHelper::USER_IS_NOT_LOGIN);
        }

        $userId = (int)$this->getParam('userId', false);
        if ($tokenUserId != $userId && $isNeed) {
            $this->Error(ErrorHelper::TOKEN_IS_INVALID);
        }

        return $tokenUserId;
    }

    /**
     * 获取通行证
     * @param $userId String 加密前的通行证
     * @return token String 加密后的通行证
     */
    public function getEncodeToken($userId)
    {
        return \common\uitl\Encrypt::authcode('dearbaobeiapi_' . $userId, 'ENCODE', 9999999);
    }

    /**
     * 获取通行证
     * @param tokenStr String 加密前的通行证
     * @return token String 加密后的通行证
     */
    public function getDecodeToken($tokenStr)
    {
        $tokenStr = \common\uitl\Encrypt::authcode($tokenStr, 'DECODE');
        return str_replace('dearbaobeiapi_', '', $tokenStr);
    }
}