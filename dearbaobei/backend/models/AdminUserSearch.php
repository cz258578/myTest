<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use \backend\models\AdminRole;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property integer $role_id
 * @property string $small_avator
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $status
 * @property string $create_time
 */
class AdminUserSearch extends Admin
{
    /**
     * 查询 管理员列表
     */
    public function getAdminUserList($params){
        $username = isset($params['username']) ? $params['username'] : '';
        $role_id = isset($params['role_id']) ? (int)$params['role_id'] : 0;

        //搜索
        $where = '';
        $qparams = [];

        if($username){
            $where .= ' au.username LIKE "%'.$username.'%"';
        }

        if($role_id){
            $where .= ' au.role_id = :role_id';
            $qparams[':role_id'] = $role_id;
        }

        //排序
        $orderBy = 'au.status DESC,au.id ASC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = Admin::find()->from(Admin::tableName().' as au')
            ->leftJoin(AdminRole::tableName().' as ar ','au.role_id = ar.id')
            ->select('au.*,
            ar.name as role_name')
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
     * 保存 管理员
     */
    public function saveAdminUser($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = Admin::findOne($id);
        if( ! $models){
            $models = new Admin();
            $models->last_login_ip = '';
            $models->last_login_time = 0;
            $models->status = 1;
            $models->create_time = time();
            $models->role_id = 0;
        }

        $models->username = $params['username'];
        if(!empty($params['password'])){
            $models->password = Admin::encryptPwd($params['password']);
        }
        $models->name = $params['name'];

        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = $models->getErrors();
        }

        return $error;
    }
}
