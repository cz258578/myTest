<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher_change_log}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $teacher_id
 * @property string $operation_name
 * @property string $opreation_id
 * @property integer $desc
 * @property integer $type
 * @property string $note
 * @property string $change_date
 * @property string $create_time
 */
class TeacherChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher_change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'teacher_id', 'opreation_tea_id', 'type', 'change_date', 'create_time'], 'integer'],
            [['note','desc'], 'string'],
            [['operation_name'], 'string', 'max' => 20]
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
            'teacher_id' => '老师ID',
            'operation_name' => '操作人姓名',
            'opreation_tea_id' => '操作人ID',
            'desc' => '变动简介',
            'type' => '异动类型（1开始休假，2结束休假，3离职账户冻结，4退休账户冻结，5复职账户启用，6部门调动，7职务调动）',
            'note' => '备注',
            'change_date' => '变动时间',
            'create_time' => '创建时间',
        ];
    }
}
