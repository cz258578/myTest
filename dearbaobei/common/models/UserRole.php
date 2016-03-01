<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_role}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $bloc_id
 * @property string $school_id
 * @property string $modules_ids
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'school_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '职务名称',
            'bloc_id' => '集团ID',
            'school_id' => '学校ID',
            'modules_ids' => '用户模块ID组',
        ];
    }
}
