<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $small_avator
 * @property string $username
 * @property string $password
 * @property integer $student_id
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
     * 整理 User表 插入 数据
     */
    public static function userInit($dataInfo){
        $UserModels = new User();

        $UserModels->name = $dataInfo['name'];
        /*$UserModels->qq = $dataInfo['qq'];
        $UserModels->email = $dataInfo['email'];
        $UserModels->weixin = $dataInfo['weixin'];*/
        $UserModels->phone = $dataInfo['phone'];
        $UserModels->password = $dataInfo['password'];
        $UserModels->teacher_id = $dataInfo['teacher_id'];
        $UserModels->is_family = 0;
        $UserModels->status = 1;
        $UserModels->create_time = time();

        if( ! $UserModels->save()){
            throw new \Exception("User表保存失败");
        }

        return $UserModels;
    }
}
