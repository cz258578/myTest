<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_student}}".
 *
 * @property string $user_id
 * @property string $student_id
 * @property string $relationship
 */
class UserStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_student}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'student_id', 'relationship'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '账号ID',
            'student_id' => '学生ID',
            'relationship' => '关系ID',
        ];
    }
}
