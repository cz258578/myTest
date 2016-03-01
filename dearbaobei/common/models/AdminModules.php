<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_modules}}".
 *
 * @property string $id
 * @property string $name
 * @property string $module_addr
 * @property string $action_addr
 * @property string $parent_str
 * @property integer $parent_id
 * @property integer $is_show
 * @property integer $status
 * @property integer $sort
 * @property string $create_time
 */
class AdminModules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_modules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'create_time','parent_id','is_show','sort'], 'integer'],
            [['name'], 'string', 'max' => 20],
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
            'parent_str' => '父类ID 集合',
            'parent_id' => '父类ID',
            'is_show' => '显示隐藏0隐藏，1显示',
            'status' => '状态(0禁用, 1正常)',
            'sort' => '排序',
            'create_time' => '创建时间',
        ];
    }
}
