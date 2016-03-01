<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%student_profile}}".
 *
 * @property string $id
 * @property string $student_id
 * @property string $census_way
 * @property string $address
 * @property string $demand
 * @property integer $health_status
 * @property integer $is_allergy
 * @property string $commom_disease
 * @property integer $is_predisease
 * @property integer $is_health_form
 * @property integer $is_health_verify
 * @property integer $blood_type
 * @property integer $is_ccine
 * @property string $interest
 * @property string $note
 */
class StudentProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%student_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'health_status', 'is_allergy', 'is_predisease', 'is_health_form', 'is_health_verify', 'blood_type', 'is_ccine'], 'integer'],
            [['address', 'demand', 'commom_disease', 'interest'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'student_id' => '学生ID',
            'census_way' => '户籍（1城市，2农村）',
            'address' => '地址',
            'demand' => '学生要求',
            'health_status' => '健康状况1健康，2一般，3较弱',
            'is_allergy' => '有无过敏史1有，0无',
            'commom_disease' => '易患何种病',
            'is_predisease' => '有无先天病1有，0无',
            'is_health_form' => '有无体检表',
            'is_health_verify' => '有无验证证明1有，0无',
            'blood_type' => '血型（0未知,1A,2B,3O,4AB）',
            'is_ccine' => '有否接种复印件1有，0无',
            'interest' => '兴趣爱好',
            'note' => '备注',
        ];
    }
}
