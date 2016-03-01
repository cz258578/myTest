<?php

namespace common\uitl;

//use Yii;
/**
 * 错误提示
 *
 * @version 1.0
 * @author weinengyu
 */
class ErrorHelper
{
    
    private static $_msg = [];

    /* 全局相关 */
    const GLOBAL_INVALID_PARAM = 10000;
    const GLOBAL_DB_FAILED = 10001;
    const USER_IS_NOT_LOGIN = 10002;
    const TOKEN_IS_INVALID = 10003;

    /* 用户相关 */
    const USER_PHONE_EXISTS = 20001;
    const USER_PHONE_REQUIRED = 20002;
    const USER_PASSWORD_REQUIRED = 20003;
    const USER_NOT_FOUND = 20004;
    const USER_INVALID_PASSWD = 20005;
    const USER_IS_BINDED_STUDENT = 20006;
    const USER_NOT_BINDED_STUDENT = 20007;
    const USER_BINDED_STUDENT_IS_EMPTY = 20008;

    /* 学生相关 */
    const STUDENT_NO_EXISTS = 30001;

    /* 空间主题相关 */
    const SPACE_THEME_NO_PERMISSIONS_COMMENT = 40001;
    const SPACE_THEME_NO_ALLOW_REPLY = 40002;
    const SPACE_THEME_IS_PRAISED = 40003;

    /* 短信相关 */
    const SMS_APPKEY_IS_EMPTY = 90001;
    const SMS_APPKEY_IS_INVALID = 90002;
    const SMS_PHONE_IS_EMPTY = 90003;
    const SMS_PHONE_IS_INVALID = 90004;
    const SMS_CODE_IS_EMPTY = 90005;
    const SMS_CODE_IS_FREQUENTLY = 90006;
    const SMS_CODE_IS_ERROR = 90007;
    const SMS_NO_OPEN_SERVER_SWITCH = 90008;

    
    private static function _init()
    {
        self::$_msg[self::GLOBAL_INVALID_PARAM] = '参数错误';
        self::$_msg[self::GLOBAL_DB_FAILED] = '操作失败, 请检查您的网络后重试';
        self::$_msg[self::USER_IS_NOT_LOGIN] = '您未登录, 请先登录！';
        self::$_msg[self::TOKEN_IS_INVALID] = '通行证已失效';

        self::$_msg[self::USER_PHONE_EXISTS] = '手机号已经被注册';
        self::$_msg[self::USER_PHONE_REQUIRED] = '请填写正确的手机号';
        self::$_msg[self::USER_PASSWORD_REQUIRED] = '密码长度不符合规范';
        self::$_msg[self::USER_NOT_FOUND] = '账户不存在';
        self::$_msg[self::USER_INVALID_PASSWD] = '密码错误';
        self::$_msg[self::USER_IS_BINDED_STUDENT] = '该账号已经绑定了此学生了, 请勿重复绑定';
        self::$_msg[self::USER_NOT_BINDED_STUDENT] = '该账号没有邦定该学生';
        self::$_msg[self::USER_BINDED_STUDENT_IS_EMPTY] = '该账号没有邦定任何学生';


        self::$_msg[self::SPACE_THEME_NO_PERMISSIONS_COMMENT] = '没有权限回复该主题';
        self::$_msg[self::SPACE_THEME_NO_ALLOW_REPLY] = '该主题不允许回复';
        self::$_msg[self::SPACE_THEME_IS_PRAISED] = '主题不允许重复点赞';
        
        self::$_msg[self::STUDENT_NO_EXISTS] = '学生不存在';

        self::$_msg[self::SMS_APPKEY_IS_EMPTY] = 'AppKey为空';
        self::$_msg[self::SMS_APPKEY_IS_INVALID] = 'AppKey无效';
        self::$_msg[self::SMS_PHONE_IS_EMPTY] = '手机号码为空';
        self::$_msg[self::SMS_PHONE_IS_INVALID] = '手机号码格式错误';
        self::$_msg[self::SMS_CODE_IS_EMPTY] = '请求校验的验证码为空';
        self::$_msg[self::SMS_CODE_IS_FREQUENTLY] = '请求校验验证码频繁';
        self::$_msg[self::SMS_CODE_IS_ERROR] = '验证码错误';
        self::$_msg[self::SMS_NO_OPEN_SERVER_SWITCH] = '没有打开服务端验证开关';
    }
    
    public static function getMsg($_errID)
    {
        if (empty(self::$msg))
        {
            self::_init();
        }
        if (isset(self::$_msg[$_errID]))
        {
            return self::$_msg[$_errID];
        }
        return '未指定的错误';
    }
}
