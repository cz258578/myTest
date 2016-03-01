<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher_profile}}".
 *
 * @property string $id
 * @property integer $teacher_id
 * @property string $worknumber
 * @property string $qq
 * @property string $weixin
 */
class TeacherProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'worknumber'], 'integer'],
            [['qq', 'weixin'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'teacher_id' => '老师ID',
            'worknumber' => '工号',
            'qq' => 'QQ号码',
            'weixin' => '微信账号',
        ];
    }
}
