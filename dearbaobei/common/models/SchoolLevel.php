<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%school_level}}".
 *
 * @property string $id
 * @property string $title
 * @property string $price
 * @property string $student_num
 */
class SchoolLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'student_num'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'title' => '等级标题',
            'price' => '价格(一年)',
            'student_num' => '学生数量',
        ];
    }
}
