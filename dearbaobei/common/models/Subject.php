<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "{{%subject}}".
 *
 * @property string $id
 * @property string $name
 * @property string $school_id
 * @property string $sort
 * @property integer $status
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'sort', 'status'], 'integer'],
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
            'name' => '科目名称',
            'school_id' => '学校ID',
            'sort' => '排序',
            'status' => '状态（0禁用, 1正常）',
        ];
    }
}
