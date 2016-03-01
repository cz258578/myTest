<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%area}}".
 *
 * @property integer $id
 * @property string $area_id
 * @property string $area
 * @property string $father_id
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'father_id'], 'integer'],
            [['area'], 'string', 'max' => 60],
            [['area_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_id' => '区域ID',
            'area' => '区域名称',
            'father_id' => '城市ID',
        ];
    }

    /*
   * 根据ID获取区域名字
   */
    public static function getNameById($id){

        $area = self::find()->where(['area_id' => $id])->one();

        if( ! empty($area)){
            return $area->area;
        }else{
            return '';
        }
    }
}
