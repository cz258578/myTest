<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property integer $role_id
 * @property string $small_avator
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $status
 * @property string $create_time
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'last_login_time', 'status', 'create_time'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 10],
            [['small_avator'], 'string', 'max' => 50],
            [['last_login_ip'], 'string', 'max' => 15],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'username' => '用户账号',
            'password' => '用户密码',
            'name' => '名字',
            'role_id' => '等级权限',
            'small_avator' => '头像缩略图',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'status' => '状态(0=禁用, 1正常)',
            'create_time' => '创建时间',
        ];
    }


    /**
     * 通过用户名查找
     */
    public static function findByUsername($username){
        return self::findOne(['username'=>$username]);
    }

    /*
     * 找到user的登录密码
     */
    public static function findByPassword($password)
    {
        return User::findOne(['password'=> $password]);
    }

    public static function validatePassword($password){
        return self::encryptPwd($password);
    }

    public static function encryptPwd($password) {
        if(empty($password)) return '';
        //return Yii::$app->getSecurity()->generatePasswordHash($password);
        return md5(sha1(serialize($password)));
    }

    /**
     * 通过 用户ID查找
     */
    public static function findByUserId($id){
        return self::findOne($id);
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = self::encryptPwd($password);
        //$this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getItemByPK($id, $item='name')
    {
        if (empty($id)) return '';

        $info = self::findOne(['id' => $id]);
        return isset($info->$item) ? $info->$item : '';
    }
}
