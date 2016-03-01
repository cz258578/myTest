<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_vouchers}}".
 *
 * @property string $id
 * @property string $bloc_id
 * @property string $mini_order_money
 * @property string $credit
 * @property string $used_credit
 * @property string $used_time
 * @property string $order_id
 * @property integer $status
 * @property string $agent_id
 * @property string $note
 * @property string $end_time
 * @property string $create_time
 */
class BlocVouchers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_vouchers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'used_time', 'order_id', 'status', 'agent_id', 'end_time', 'create_time'], 'integer'],
            [['mini_order_money', 'credit', 'used_credit'], 'number'],
            [['note'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'bloc_id' => '集团ID',
            'mini_order_money' => '最少使用金额',
            'credit' => '代金卷额度',
            'used_credit' => '实际使用额度',
            'used_time' => '使用时间',
            'order_id' => '订单ID',
            'status' => '状态(0已使用，1未使用，2已过期)',
            'agent_id' => '发放代理商ID',
            'note' => '备注',
            'end_time' => '到期时间',
            'create_time' => '创建时间',
        ];
    }
}
