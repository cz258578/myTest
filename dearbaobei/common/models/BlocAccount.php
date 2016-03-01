<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_account}}".
 *
 * @property string $bloc_id
 * @property string $money
 * @property string $freeze_money
 */
class BlocAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'freeze_money'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bloc_id' => '集团ID',
            'money' => '金额',
            'freeze_money' => '冻结金额',
        ];
    }
}
