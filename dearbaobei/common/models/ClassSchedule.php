<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class_schedule}}".
 *
 * @property string $id
 * @property string $curricula_id
 * @property integer $tea_curri_sub_id
 * @property integer $week
 * @property string $course
 * @property string $start_time
 * @property integer $end_time
 */
class ClassSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['curricula_id', 'tea_curri_sub_id', 'week', 'course', 'start_time', 'end_time'], 'required'],
            [['curricula_id', 'tea_curri_sub_id', 'week', 'course'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'curricula_id' => '课程表ID',
            'tea_curri_sub_id' => '老师班级科目表ID',
            'week' => '周几',
            'course' => '第几节课',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
        ];
    }
}
