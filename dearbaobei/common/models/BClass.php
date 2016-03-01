<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $name
 * @property string $current_grade_id
 * @property string $head_teacher_id
 * @property string $create_time
 * @property integer $status
 */
class BClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'current_grade_id', 'head_teacher_id', 'create_time', 'status'], 'integer'],
            [['name'], 'string', 'max' => 10]
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
            'name' => '班级名称',
            'current_grade_id' => '当前年级',
            'head_teacher_id' => '班主任ID',
            'create_time' => '创建时间',
            'status' => '状态（1正常，2毕业）',
        ];
    }
}
