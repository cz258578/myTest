<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_alipay_log}}".
 *
 * @property string $id
 * @property string $bloc_id
 * @property string $teacher_id
 * @property string $order_number
 * @property string $money
 * @property string $type
 * @property string $account_log_id
 * @property integer $status
 * @property string $create_time
 */
class BlocAlipayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_alipay_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'teacher_id', 'account_log_id', 'status', 'create_time'], 'integer'],
            [['money'], 'number'],
            [['order_number'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 100]
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
            'teacher_id' => '操作人ID',
            'order_number' => '充值订单号',
            'money' => '充值金额',
            'type' => '充值方式',
            'account_log_id' => '账户流水ID（充值成功后写入）',
            'status' => '支付状态(1支付完成，2待支付，3已取消)',
            'create_time' => '创建时间',
        ];
    }
}
