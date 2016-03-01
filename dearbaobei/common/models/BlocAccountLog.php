<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_account_log}}".
 *
 * @property string $id
 * @property string $bloc_id
 * @property string $teacher_id
 * @property string $school_id
 * @property string $order_id
 * @property integer $type
 * @property string $money
 * @property string $alipay_order_number
 * @property string $accept_alipay
 * @property string $pay_alipay
 * @property string $note
 * @property string $create_time
 */
class BlocAccountLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_account_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'teacher_id', 'school_id', 'order_id', 'type', 'create_time'], 'integer'],
            [['teacher_id'], 'required'],
            [['money'], 'number'],
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
            'teacher_id' => '操作老师ID',
            'school_id' => '学校ID',
            'order_id' => '订单ID',
            'alipay_order_number' => '订单号',
            'accept_alipay' => '收款账户',
            'pay_alipay' => '支出账户',
            'type' => '流水类型(1入账，2出账)',
            'money' => '流水额度',
            'note' => '备注',
            'create_time' => '创建时间',
        ];
    }
}
