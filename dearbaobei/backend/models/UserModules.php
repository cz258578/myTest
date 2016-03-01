<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%user_modules}}".
 *
 * @property string $id
 * @property string $name
 * @property string $module_addr
 * @property string $action_addr
 * @property string $parent_id
 * @property string $parent_str
 * @property integer $status
 * @property integer $sort
 * @property integer $is_show
 * @property string $create_time
 */
class UserModules extends \common\models\UserModules
{
    /**
     * @param $params
     * 获取用户 模块 list
     */
    public function getUserModulesList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';

        //搜索
        $where = '';
        $qparams = [];

        if($name){
            $where .= ' um.name LIKE "%'.$name.'%" ';
            $where .= ' OR um.module_addr LIKE "%'.$name.'%" ';
            $where .= ' OR um.action_addr LIKE "%'.$name.'%" ';
        }

        //排序
        $orderBy = 'um.parent_id ASC,um.sort DESC,um.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = UserModules::find()->from(UserModules::tableName().' as um')
            ->select('um.*')
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
     * 保存 用户 模块
     */
    public function saveUserModules($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = UserModules::findOne($id);
        if( ! $models ){
            $models = new UserModules();
            $models->create_time = time();
            $models->status = 1;
        }
        $models->name = $params['name'];
        $models->action_addr = isset($params['action_addr'])?$params['action_addr']:'';
        $models->module_addr = isset($params['module_addr'])?$params['module_addr']:'';
        $models->parent_id = isset($params['parent_id']) ? (int)$params['parent_id'] : 0;
        $models->parent_str = $this->getParentStr($models->parent_id);
        $models->is_show = $params['is_show'];
        $models->sort = $params['sort'] ? (int)$params['sort'] : 0;
        $models->level = $params['level'];

        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = $models->getErrors();
        }
        return $error;
    }

    public function getParentStr($parentId){
        $models = UserModules::findOne($parentId);
        if(! $models){
            return '';
        }else{
            return ($models->parent_str ? $models->parent_str:',').$parentId.',';
        }
    }
}
