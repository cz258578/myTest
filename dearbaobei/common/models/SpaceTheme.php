<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%space_theme}}".
 *
 * @property string $id
 * @property string $content
 * @property string $class_id
 * @property string $student_id
 * @property string $user_id
 * @property integer $is_allow_reply
 * @property integer $public_type
 * @property string $praise_num
 * @property string $create_time
 */
class SpaceTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%space_theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['class_id', 'student_id', 'user_id', 'is_allow_reply', 'public_type', 'praise_num', 'create_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'class_id' => 'Class ID',
            'student_id' => 'Student ID',
            'user_id' => 'User ID',
            'is_allow_reply' => 'Is Allow Reply',
            'public_type' => 'Public Type',
            'praise_num' => 'Praise Num',
            'create_time' => 'Create Time',
        ];
    }
}
