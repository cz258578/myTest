<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property string $id
 * @property string $city_id
 * @property string $city
 * @property string $father_id
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'father_id'], 'integer'],
            [['city'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'city_id' => '城市ID',
            'city' => '城市名称',
            'father_id' => '省份ID',
        ];
    }

     /*
   * 根据ID获取城市名字
   */
    public static function getNameById($id){

        $city = self::find()->where(['city_id' => $id])->one();

        if( ! empty($city)){
            return $city->city;
        }else{
            return '';
        }
    }
}
