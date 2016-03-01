<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_account}}".
 *
 * @property string $bloc_id
 * @property string $money
 * @property string $freeze_money
 */
class BlocAccount extends \common\models\BlocAccount
{
    /**
     * 整理 Bloc_account表 插入 数据
     */
    public static function blocAccountInit($BlocModels){
        $BlocAccountModels = new BlocAccount();

        $BlocAccountModels->bloc_id = $BlocModels['id'];
        $BlocAccountModels->money = 0;
        $BlocAccountModels->freeze_money = 0;

        if( ! $BlocAccountModels->save()){
            throw new \Exception("Bloc_account表保存失败");
        }

        return $BlocAccountModels;
    }
}
