<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tea_curri_subject}}".
 *
 * @property string $id
 * @property string $teacher_id
 * @property string $subject_id
 * @property string $curricula_id
 */
class TeaCurriSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tea_curri_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'subject_id', 'curricula_id'], 'integer']
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
            'subject_id' => '科目ID',
            'curricula_id' => '课程表ID',
        ];
    }
}
