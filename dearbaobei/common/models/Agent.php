<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%agent}}".
 *
 * @property string $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property string $qq
 * @property string $weixin
 * @property string $email
 * @property string $phone
 * @property integer $type
 * @property string $idcard
 * @property integer $level
 * @property integer $status
 * @property string $create_time
 */
class Agent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'level', 'status', 'create_time'], 'integer'],
            [['username'], 'string', 'max' => 100],
            [['name', 'password', 'weixin', 'email'], 'string', 'max' => 50],
            [['qq', 'phone'], 'string', 'max' => 20],
            [['idcard'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'username' => '用户名',
            'name' => '真实姓名',
            'password' => '密码',
            'qq' => 'qq',
            'weixin' => '微信',
            'email' => 'email',
            'phone' => '手机',
            'type' => '类型(1,企业，2个人)',
            'idcard' => '身份证',
            'level' => '级别（1省代理，2城市代理，3区域代理）',
            'status' => '状态（0禁用，1正常）',
            'create_time' => '创建时间',
        ];
    }
}
