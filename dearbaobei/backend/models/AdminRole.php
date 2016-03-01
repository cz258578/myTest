<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%admin_role}}".
 *
 * @property string $id
 * @property string $name
 * @property string $modules_ids
 */
class AdminRole extends \common\models\AdminRole
{

    /**
     * admin role list
     */
    public function getAdminRoleList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';
        //搜索
        $where = '';
        $qparams = [];

        if($name){
            $where .= 'ar.name LIKE "%'.$name.'%"';
        }

        //排序
        $orderBy = 'ar.id ASC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = AdminRole::find()->from(AdminRole::tableName().' as ar')
            ->select('ar.*')
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

    public function saveAdminRole($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = AdminRole::findOne($id);
        if( ! $models ){
            $models = new AdminRole();
        }

        if(isset($params['name']) && !empty($params['name'])){
            $models->name = $params['name'];
        }

        if(isset($params['modules_ids']) && !empty($params['modules_ids'])){
            $models->modules_ids = ','.implode(',',$params['modules_ids']).',';
        }

        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = $models->getErrors();
        }
        return $error;
    }
}
