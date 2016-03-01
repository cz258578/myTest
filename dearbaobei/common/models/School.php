<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%school}}".
 *
 * @property string $id
 * @property string $name
 * @property string $first_termname
 * @property integer $first_term_month
 * @property string $last_termname
 * @property integer $last_term_month
 * @property integer $type
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property string $lng
 * @property string $lat
 * @property string $bloc_id
 * @property string $address
 * @property string $create_time
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_term_month', 'last_term_month', 'type', 'province_id', 'city_id', 'area_id', 'bloc_id', 'create_time'], 'integer'],
            [['lng', 'lat'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['first_termname', 'last_termname'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称',
            'first_termname' => '上学期',
            'first_term_month' => '上学期开始月份',
            'last_termname' => '下学期',
            'last_term_month' => '下学期开始月份',
            'type' => '学校类型',
            'province_id' => '省ID',
            'city_id' => '城市ID',
            'area_id' => '区域ID',
            'lng' => '经度',
            'lat' => '纬度',
            'bloc_id' => '集团ID',
            'address' => '详细地址',
            'create_time' => '创建时间',
        ];
    }
}
