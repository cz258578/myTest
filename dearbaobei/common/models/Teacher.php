<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property string $name
 * @property string $bloc_id
 * @property string $school_id
 * @property string $structure_id
 * @property integer $sex
 * @property integer $entry_date
 * @property integer $duty_id
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property integer $status
 * @property string $create_time
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'school_id', 'structure_id', 'sex', 'year', 'month', 'day', 'entry_date', 'duty_id', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '姓名',
            'bloc_id' => '集团ID',
            'school_id' => '学校ID',
            'structure_id' => '部门ID',
            'sex' => '性别（0未知，1男，2女）',
            'year' => '年',
            'month' => '月',
            'day' => '年',
            'entry_date' => '入职时间',
            'duty_id' => '职务ID',
            'status' => '状态（0禁用，1正常）',
            'create_time' => '创建时间',
        ];
    }
}
