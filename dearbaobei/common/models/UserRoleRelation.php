<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_role_relation}}".
 *
 * @property string $role_id
 * @property string $bloc_id
 * @property string $school_id
 * @property string $class_id
 * @property string $user_id
 * @property integer $is_master
 */
class UserRoleRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_role_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'bloc_id', 'school_id', 'class_id', 'user_id', 'is_master'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '用户角色ID',
            'bloc_id' => '所管理的集团ID',
            'school_id' => '所管理的学校ID',
            'class_id' => '所管理的班级ID',
            'user_id' => '用户ID',
            'is_master' => '是否是主职业(0不是，1是)',
        ];
    }
}
