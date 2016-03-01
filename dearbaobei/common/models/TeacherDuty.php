<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher_duty}}".
 *
 * @property string $id
 * @property string $duty_name
 * @property string $bloc_id
 * @property string $sort
 * @property integer $status
 * @property string $creat_time
 */
class TeacherDuty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher_duty}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bloc_id', 'sort', 'status', 'create_time'], 'integer'],
            [['duty_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'duty_name' => '职务名称',
            'bloc_id' => '所属集团ID',
            'sort' => '排序',
            'status' => '0禁用，1正常',
            'create_time' => '创建时间',
        ];
    }
}
