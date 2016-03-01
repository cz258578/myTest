<?php

namespace backend\models;

use frontend\models\Teacher;
use Yii;
use yii\data\ActiveDataProvider;

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
class BlocAlipayLog extends \common\models\BlocAlipayLog
{
    /**
     *
     */
    public function getBlocAlipayLogList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';
        //搜索
        $where = '';
        $qparams = [];

        if($name){
            $where .= 'b.name LIKE "%'.$name.'%"';
        }

        //排序
        $orderBy = 'bal.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = BlocAlipayLog::find()->from(BlocAlipayLog::tableName().' AS bal')
            ->leftJoin(Bloc::tableName().' AS b','bal.bloc_id = b.id ')
            ->leftJoin(Teacher::tableName().' AS t','bal.teacher_id = t.id')
            ->select('bal.*,
            b.name AS bloc_name,
            t.name AS teacher_name
            ')
            ->where($where)->params($qparams)
            ->orderBy($orderBy)->asArray();

        $dataProvider = new ActiveDataProvider([
            'sort' => [],
            'pagination' => [
                'pagesize' => $pageSize,
                'page' => $currentPage
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
