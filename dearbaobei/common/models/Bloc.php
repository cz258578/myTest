<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $contacts
 * @property integer $sex
 * @property string $contact_phone
 * @property string $weixin
 * @property string $qq
 * @property string $email
 * @property integer $status
 * @property string $school_limit_num
 * @property string $addr
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property integer $admin_user_id
 * @property string $bespeak_id
 * @property string $agent_id
 * @property string $create_time
 */
class Bloc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'school_limit_num', 'province_id', 'city_id', 'area_id', 'bespeak_id', 'agent_id', 'create_time'], 'integer'],
            [['name', 'contacts', 'contact_phone', 'weixin', 'qq', 'email'], 'string', 'max' => 50],
            [['addr'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '集团名称',
            'contacts' => '联系人',
            'sex' => '性别',
            'contact_phone' => '联系号码',
            'weixin' => '微信',
            'qq' => 'QQ',
            'email' => 'email',
            'status' => '状态(0禁用, 1正常, 2过期)',
            'school_limit_num' => '学校限制数量',
            'addr' => '地址',
            'province_id' => '省ID',
            'city_id' => '城市ID',
            'area_id' => '区域ID',
            'admin_user_id' => '客服ID',
            'bespeak_id' => '集团预约ID',
            'agent_id' => '推荐代理商ID',
            'create_time' => '创建时间',
        ];
    }
}
