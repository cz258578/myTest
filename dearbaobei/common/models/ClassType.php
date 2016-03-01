<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%class_type}}".
 *
 * @property string $id
 * @property string $type_name
 * @property string $bloc_id
 * @property string $sort
 * @property integer $status
 * @property string $create_time
 */
class ClassType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'sort', 'status', 'create_time'], 'integer'],
            [['type_name'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type_name' => '班级类型名称',
            'bloc_id' => '集团ID',
            'sort' => '排序',
            'status' => '0禁用，1正常',
            'create_time' => '创建时间',
        ];
    }
}
