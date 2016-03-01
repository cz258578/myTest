<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%student_bespeak_memo}}".
 *
 * @property string $id
 * @property string $bespeak_id
 * @property string $enter_teacher_id
 * @property string $desc
 * @property string $create_time
 */
class StudentBespeakMemo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%student_bespeak_memo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bespeak_id', 'create_time'], 'integer'],
            [['desc'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'bespeak_id' => '学生预约ID',
            'enter_teacher_id' => '录入人ID',
            'desc' => '描述',
            'create_time' => '创建时间',
        ];
    }
}
