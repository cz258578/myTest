<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher_subject}}".
 *
 * @property string $teacher_id
 * @property string $subject_id
 */
class TeacherSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'subject_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacher_id' => '老师ID',
            'subject_id' => '科目ID',
        ];
    }
}
