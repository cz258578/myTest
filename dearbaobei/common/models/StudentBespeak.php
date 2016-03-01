<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%student_bespeak}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $bespeak_grade_id
 * @property string $name
 * @property integer $sex
 * @property string $birthday
 * @property string $home_addr
 * @property string $parent_name
 * @property string $parent_phone
 * @property string $relationship
 * @property string $access_way
 * @property string $receiver
 * @property integer receiver_tea_id
 * @property string $visit_time
 * @property integer $bespeak_enter_time
 * @property integer $health_status
 * @property integer $is_local
 * @property integer $is_fresh
 * @property integer $is_teacherson
 * @property string $demand
 * @property string $note
 * @property integer $status
 * @property string $create_time
 */
class StudentBespeak extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%student_bespeak}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'bespeak_grade_id', 'sex', 'status', 'create_time','receiver_tea_id'], 'required'],
            [['school_id', 'bespeak_grade_id', 'sex', 'birthday', 'visit_time', 'bespeak_enter_time', 'health_status', 'is_local', 'receiver_tea_id',
                'is_fresh', 'is_teacherson', 'status', 'create_time','relationship'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['home_addr', 'note'], 'string', 'max' => 200],
            [['parent_name', 'parent_phone', 'receiver'], 'string', 'max' => 20],
            [['demand'], 'string', 'max' => 100]
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
            'bespeak_grade_id' => '意向年级ID',
            'name' => '姓名',
            'sex' => '性别（0未知，1男，2 女）',
            'birthday' => '出生年月日',
            'home_addr' => '家庭住址',
            'parent_name' => '家长姓名',
            'parent_phone' => '家长号码',
            'relationship' => '家长关系',
            'access_way' => '获取途径',
            'receiver' => '接待人',
            'receiver_tea_id' => '老师ID',
            'visit_time' => '来访时间',
            'bespeak_enter_time' => '预报到时间',
            'health_status' => '健康状况（1健康 3 一般 5较差）',
            'is_local' => '是否本地生（0未选择，1是，2不是）',
            'is_fresh' => '是否新生（0未选择，1是，2不是）',
            'is_teacherson' => '是否教工子弟（0未选择，1是，2不是）',
            'demand' => '学生要求',
            'note' => '学校备注',
            'status' => '状态 （1等待审批2 通过 3未通过）',
            'create_time' => '创建时间',
        ];
    }
}
