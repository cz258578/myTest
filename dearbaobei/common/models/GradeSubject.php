<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%grade_subject}}".
 *
 * @property string $grade_id
 * @property string $subject_id
 */
class GradeSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grade_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade_id', 'subject_id'],'integer'],
            [['grade_id', 'subject_id'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'grade_id' => '年级ID',
            'subject_id' => '科目ID',
        ];
    }
}
