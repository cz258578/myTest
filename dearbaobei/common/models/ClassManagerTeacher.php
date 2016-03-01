<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class_manager_teacher}}".
 *
 * @property string $class_id
 * @property string $teacher_id
 */
class ClassManagerTeacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_manager_teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'teacher_id'], 'required'],
            [['class_id', 'teacher_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'class_id' => '班级ID',
            'teacher_id' => '老师ID',
        ];
    }
}
