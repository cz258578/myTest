<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_structure}}".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property string $bloc_id
 * @property string $school_id
 * @property string $parent_str
 * @property string $sort_id
 * @property string $create_time
 */
class BlocStructure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_structure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'bloc_id', 'school_id', 'sort_id', 'create_time'], 'integer'],
            [['name', 'parent_str'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称',
            'parent_id' => '父级ID',
            'bloc_id' => '集团ID',
            'school_id' => '学校ID',
            'parent_str' => '所有父级ID字符串',
            'sort_id' => '排序字段',
            'create_time' => '创建时间',
        ];
    }
}
