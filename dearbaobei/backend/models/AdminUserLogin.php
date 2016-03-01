<?php

namespace backend\models;

use Yii;
use yii\base\Model;
/**
 *
 */
class AdminUserLogin extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;

    private $_user = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '账户',
            'password' => '密码',
            'rememberMe' => '记住密码',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if($user && $user->status != 1){
                $this->addError($attribute, '账户已被禁用.');
            }
            if (!$user || $user->password != Admin::validatePassword($this->password)) {
                $this->addError($attribute, '账户或密码不正确.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $userinfo = $this->getUser();
            //跟新 登陆信息
            $this->setAdminUserInfoById($userinfo->id);
            return Yii::$app->user->login($userinfo, $this->rememberMe ? 3600 * 24 * 7 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->username);
        }
        return $this->_user;
    }

    /**
     * 跟新登陆信息
     */
    public function setAdminUserInfoById($id){
        $models = Admin::findOne($id);
        $models->last_login_time = time();
        $models->last_login_ip = yii::$app->request->userIP;
        $models->save();
    }
}
