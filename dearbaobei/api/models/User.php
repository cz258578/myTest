<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $small_avator
 * @property string $username
 * @property string $password
 * @property integer $teacher_id
 * @property integer $is_family
 * @property string $phone
 * @property string $email
 * @property integer $role_id
 * @property string $role_ids
 * @property integer $status
 * @property integer $create_time
 */
class User extends \common\models\User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'teacher_id', 'is_family', 'phone', 'status', 'create_time'], 'required'],
            [['teacher_id', 'is_family', 'status', 'create_time'], 'integer'],
            [['name', 'username'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * 加密密码
     */
    public static function encryptPwd($password) {
        if(empty($password)) return '';
        //return Yii::$app->getSecurity()->generatePasswordHash($password);
        return md5(sha1(serialize($password)));
    }
}
