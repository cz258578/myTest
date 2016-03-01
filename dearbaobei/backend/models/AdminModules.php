<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%admin_modules}}".
 *
 * @property string $id
 * @property string $name
 * @property string $module_addr
 * @property string $action_addr
 * @property integer $status
 * @property string $create_time
 */
class AdminModules extends \common\models\AdminModules
{

    /*
     *  list admin modules
     */
    public function getAdminModulesList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';

        //搜索
        $where = '';
        $qparams = [];

        if($name){
            $where .= ' am.name LIKE "%'.$name.'%" ';
            $where .= ' OR am.module_addr LIKE "%'.$name.'%" ';
            $where .= ' OR am.action_addr LIKE "%'.$name.'%" ';
        }

        //排序
        $orderBy = 'am.parent_id ASC,am.sort DESC,am.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = AdminModules::find()->from(AdminModules::tableName().' as am')
            ->select('am.*')
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

    /**
     * 保存 后台 模块
     */
    public function saveAdminModules($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = AdminModules::findOne($id);
        if( ! $models ){
            $models = new AdminModules();
            $models->create_time = time();
            $models->status = 1;
        }
        $models->name = $params['name'];
        $models->action_addr = $params['action_addr'];
        $models->module_addr = $params['module_addr'];
        $models->parent_id = isset($params['parent_id']) ? (int)$params['parent_id'] : 0;
        $models->parent_str = $this->getParentStr($models->parent_id);
        $models->is_show = $params['is_show'];
        $models->sort = $params['sort'] ? (int)$params['sort'] : 0;

        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = $models->getErrors();
        }
        return $error;
    }

    public function getParentStr($parentId){
        $models = AdminModules::findOne($parentId);
        if(! $models){
            return '';
        }else{
            return ($models->parent_str ? $models->parent_str:',').$parentId.',';
        }
    }
}
