<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $name
 * @property string $small_avator
 * @property string $username
 * @property string $password
 * @property string $teacher_id
 * @property integer $is_family
 * @property string $phone
 * @property integer $is_public_phone
 * @property string $email
 * @property string $qq
 * @property string $weixin
 * @property string $auth_key
 * @property integer $status
 * @property string $create_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'is_family', 'is_public_phone', 'status', 'create_time'], 'integer'],
            [['name', 'username'], 'string', 'max' => 20],
            [['small_avator', 'auth_key'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 11],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '用户姓名',
            'small_avator' => '头像缩略图',
            'username' => '用户账号',
            'password' => '密码',
            'teacher_id' => '老师ID',
            'is_family' => '状态（0不是 1 是）家长',
            'phone' => '手机号码',
            'is_public_phone' => '是否公共手机号码（0不公开, 1公开）',
            'email' => '邮箱',
            'qq' => 'QQ号码',
            'weixin' => '微信号码',
            'auth_key' => '用户密匙',
            'status' => '状态(0禁用, 1正常)',
            'create_time' => '创建时间',
        ];
    }
}
