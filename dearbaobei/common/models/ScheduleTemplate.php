<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%schedule_template}}".
 *
 * @property string $id
 * @property string $school_id
 * @property string $grade_id
 * @property integer $week_type
 * @property string $content
 */
class ScheduleTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schedule_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'grade_id', 'week_type'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string']
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
            'grade_id' => '年级ID',
            'week_type' => '周末类型',
            'content' => '内容',
        ];
    }
}
