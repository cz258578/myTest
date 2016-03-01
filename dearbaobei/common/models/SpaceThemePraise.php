<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%space_theme_praise}}".
 *
 * @property string $id
 * @property string $theme_id
 * @property string $user_id
 * @property string $create_time
 */
class SpaceThemePraise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%space_theme_praise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id', 'user_id', 'create_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'theme_id' => 'Theme ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
