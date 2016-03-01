<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%student_change_log}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $current_class_id
 * @property string $student_id
 * @property string $operation_name
 * @property string $operation_tea_id
 * @property integer $type
 * @property string $note
 * @property string $change_date
 * @property string $create_time
 */
class StudentChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%student_change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'current_class_id', 'student_id', 'operation_tea_id', 'type', 'change_date', 'create_time'], 'integer'],
            [['operation_name', 'operation_tea_id', 'type', 'change_date'], 'required'],
            [['note', 'desc'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'school_id' => '学校ID',
            'current_class_id' => '当前班级ID',
            'student_id' => '学生ID',
            'operation_name' => '操作人姓名',
            'operation_tea_id' => '操作人老师ID',
            'type' => '异动类型（1请假 5旷课 9退学）',
            'desc' => '变动简介',
            'note' => '备注',
            'change_date' => '变动日期',
            'create_time' => '创建时间',
        ];
    }
}
