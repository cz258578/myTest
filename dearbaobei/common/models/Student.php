<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%student}}".
 *
 * @property string $id
 * @property string $name
 * @property string $student_no
 * @property integer $sex
 * @property string $school_id
 * @property string $class_id
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property integer $status
 * @property string $phone
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%student}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'day', 'status', 'phone'], 'required'],
            [['sex', 'school_id', 'class_id', 'year', 'month', 'day', 'status', 'phone'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['student_no'], 'string', 'max' => 30]
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
            'student_no' => '学号',
            'sex' => '性别(0未知, 1男, 2女)',
            'school_id' => '学校ID',
            'class_id' => '班级ID',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'status' => '状态(0禁用, 1正常)',
            'phone' => '绑定家长验证',
        ];
    }
}
