<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class_change_log}}".
 *
 * @property string $id
 * @property integer $bloc_id
 * @property string $school_id
 * @property string $class_id
 * @property string $operation_name
 * @property string $operation_teacher_id
 * @property integer $type
 * @property string $desc
 * @property string $note
 * @property string $change_date
 * @property string $create_time
 */
class ClassChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id'], 'required'],
            [['id', 'bloc_id', 'school_id', 'class_id', 'operation_teacher_id', 'type', 'change_date', 'create_time'], 'integer'],
            [['operation_name'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'bloc_id' => 'Bloc ID',
            'school_id' => '学校ID',
            'class_id' => '班级ID',
            'operation_name' => '操作人姓名',
            'operation_teacher_id' => '操作人老师ID',
            'type' => '类型(1升级, 2毕业, 3解散）',
            'desc' => '简介',
            'note' => '备注',
            'change_date' => '变动日期',
            'create_time' => '创建时间',
        ];
    }
}
