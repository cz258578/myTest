<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class_curricula}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $class_id
 * @property integer $year
 * @property integer $term_type
 * @property string $crrent_grade_id
 * @property string $sort
 * @property integer $is_active
 */
class ClassCurricula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_curricula}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'class_id', 'year', 'term_type', 'crrent_grade_id', 'sort', 'is_active', 'morning_has_num'], 'integer']
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
            'class_id' => '班级ID',
            'year' => '年份',
            'term_type' => '学期类型',
            'crrent_grade_id' => '年级ID',
            'sort' => '排序',
            'is_active' => '是否活动',
        ];
    }
}
