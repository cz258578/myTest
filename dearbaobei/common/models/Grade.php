<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%grade}}".
 *
 * @property string $id
 * @property string $name
 * @property string $school_id
 * @property string $sort
 * @property integer $is_end_grade
 */
class Grade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grade}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['school_id','sort','name','status'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'name' => '',
            'school_id' => '',
            'sort' => '',
            'status' => '',
        ];
    }
}
