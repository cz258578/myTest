<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property string $id
 * @property string $name
 * @property string $describe
 * @property string $group
 * @property string $type
 * @property integer $rank
 * @property string $value status
 * @property integer $status
 * @property string $create_time
 */
class SystemConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['describe'], 'string', 'max' => 100],
            [['group', 'type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '字段EN',
            'describe' => '描述',
            'group' => '分组',
            'type' => '值得类型',
            'rank' => '排序',
            'value' => '值',
            'status' => '状态 0禁用，1正常',
            'create_time' => '创建时间',
        ];
    }
}
