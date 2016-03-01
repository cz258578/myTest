<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_order}}".
 *
 * @property string $id
 * @property string $bloc_id
 * @property string $teacher_id
 * @property string $school_id
 * @property integer $type
 * @property integer $pay_type
 * @property string $account_money
 * @property string $vouchers_money
 * @property string $all_money
 * @property integer $status
 * @property string $pay_time
 * @property string $start_time
 * @property string $end_time
 * @property string $create_time
 */
class BlocOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bloc_id', 'teacher_id', 'school_id', 'status', 'pay_time', 'start_time', 'end_time', 'create_time'], 'integer'],
            [['account_money', 'vouchers_money', 'all_money'], 'number']
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
            'school_id' => '学校ID',
            'account_money' => '金钱操作金额',
            'vouchers_money' => '代金卷操作金额',
            'all_money' => '总操作金额',
            'status' => '支付状态(1支付完成，2待支付，3已取消)',
            'pay_time' => '支付时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'create_time' => '创建时间',
        ];
    }
}
