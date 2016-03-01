<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_modules}}".
 *
 * @property string $id
 * @property string $name
 * @property string $module_addr
 * @property string $action_addr
 * @property string $parent_id
 * @property string $parent_str
 * @property integer $status
 * @property integer $sort
 * @property integer $is_show
 * @property integer $level
 * @property string $create_time
 */
class UserModules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_modules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'sort', 'is_show', 'level', 'create_time'], 'integer'],
            [['name', 'parent_str'], 'string', 'max' => 20],
            [['module_addr', 'action_addr'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '模块名称',
            'module_addr' => '模块地址',
            'action_addr' => '模块方法地址',
            'parent_id' => '父类ID',
            'parent_str' => '第四级的父类ids',
            'status' => '状态(0禁用, 1正常)',
            'sort' => '排序',
            'is_show' => '是否显示(0不显示, 1显示)',
            'level' => '可用角色层级（0，1， 2， 88）',
            'create_time' => '创建时间',
        ];
    }
}
