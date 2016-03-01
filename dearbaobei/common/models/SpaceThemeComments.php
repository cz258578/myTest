<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%space_theme_comments}}".
 *
 * @property string $id
 * @property string $theme_id
 * @property string $user_id
 * @property string $replyto_user_id
 * @property string $content
 * @property string $create_time
 */
class SpaceThemeComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%space_theme_comments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id', 'user_id', 'create_time'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string']
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
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }
}
