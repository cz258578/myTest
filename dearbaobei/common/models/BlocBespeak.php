<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_bespeak}}".
 *
 * @property string $id
 * @property string $password
 * @property string $bloc_name
 * @property integer $sex
 * @property string $addr
 * @property string $contacts
 * @property string $contact_phone
 * @property string $weixin
 * @property string $qq
 * @property string $email
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property integer $access_to
 * @property integer $admin_user_id
 * @property integer $intention_type
 * @property string $next_visit_time
 * @property string $note
 * @property string $agent_id
 * @property integer $status
 * @property string $create_time
 */
class BlocBespeak extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_bespeak}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'area_id', 'access_to', 'intention_type', 'next_visit_time', 'agent_id', 'status', 'create_time'], 'integer'],
            [['password'], 'string', 'max' => 32],
            [['bloc_name', 'weixin', 'qq', 'email'], 'string', 'max' => 50],
            [['addr', 'note'], 'string', 'max' => 200],
            [['contacts', 'contact_phone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'password' => '密码',
            'bloc_name' => '集团名称',
            'sex'=>'性别',
            'addr' => '地址',
            'contacts' => '联系人',
            'contact_phone' => '联系号码',
            'weixin' => '微信',
            'qq' => 'QQ',
            'email' => 'email',
            'province_id' => '省ID',
            'city_id' => '城市ID',
            'area_id' => '区域ID',
            'access_to' => '获取途径',
            'admin_user_id' => '接待人ID',
            'intention_type' => '意向类型（1 普通。2 积极）',
            'next_visit_time' => '下次访问时间',
            'note' => '备注',
            'agent_id' => '推荐代理商ID',
            'status' => '状态（1.有效 2已注册 3 待审批）',
            'create_time' => '创建时间',
        ];
    }
}
