<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property string $id
 * @property string $name
 * @property string $describe
 * @property string $group
 * @property string $type
 * @property integer $rank
 * @property string $value
 * @property string $create_time
 */
class SystemConfig extends \common\models\SystemConfig
{

    /**
     * list system config
     */
    public function getSystemConfigList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';
        $type = isset($params['type']) ? $params['type'] : '';
        $group = isset($params['group']) ? (int)$params['group'] : 0;
        $status = isset($params['status']) ? (int)$params['status'] : -1;

        //搜索
        $where = 'sys.group >0 ';
        $qparams = [];

        if($group){
            $where .= ' AND sys.group = :group';
            $qparams[':group'] = $group;
        }

        if($name){
            $where .= ' AND (sys.name LIKE "%'.$name.'%" OR sys.describe LIKE "%'.$name.'%")';
        }

        if($type){
            $where .= ' AND sys.type = :type';
            $qparams[':type'] = $type;
        }

        if($status>-1){
            $where .= ' AND sys.status = :status';
            $qparams[':status'] = $status;
        }

        //排序
        $orderBy = 'sys.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = SystemConfig::find()->from(SystemConfig::tableName().' as sys')
            ->select('sys.*')
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
     * 保存 系统配置
     */
    public function saveSystemConfig($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = SystemConfig::findOne($id);
        if( ! $models ){
            $models = new SystemConfig();
            $models->create_time = time();
            $models->status = 1;
            $models->rank = 0;
        }
        $models->name = $params['name'];
        $models->describe = $params['describe'];
        $models->group = $params['group'];
        $models->type = $params['type'];
        $models->value = $params['value'];
        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = $models->getErrors();
        }
        return $error;
    }
}
