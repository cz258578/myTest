<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%space_theme_image}}".
 *
 * @property string $id
 * @property string $theme_id
 * @property string $thumb_path
 * @property string $image_path
 */
class SpaceThemeImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%space_theme_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id'], 'integer'],
            [['thumb_path', 'image_path'], 'string', 'max' => 100]
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
            'thumb_path' => 'Thumb Path',
            'image_path' => 'Image Path',
        ];
    }
}
