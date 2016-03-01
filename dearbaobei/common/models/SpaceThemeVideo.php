<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%space_theme_video}}".
 *
 * @property string $id
 * @property string $theme_id
 * @property string $video_path
 * @property string $cover_image
 * @property string $duration
 */
class SpaceThemeVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%space_theme_video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id'], 'integer'],
            [['video_path', 'cover_image'], 'string', 'max' => 100],
            [['duration'], 'string', 'max' => 20]
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
            'video_path' => 'Video Path',
            'cover_image' => 'Cover Image',
            'duration' => 'Duration',
        ];
    }
}
