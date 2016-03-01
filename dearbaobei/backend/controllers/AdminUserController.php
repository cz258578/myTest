<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminRole;
use backend\models\AdminUserSearch;
use common\uitl\Encrypt;
use Yii;
use yii\web\NotFoundHttpException;

/**
* 管理员 控制器
*/
class AdminUserController extends AuthBaseController
{
	
    /**
    * 功能描述
    *
    */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 管理员 首页 infojson
     */
    public function actionInfojson(){
        $params = yii::$app->request->post();

        $models = new AdminUserSearch();
        $query = $models->getAdminUserList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){
                $list[$k]['last_login_time_name'] = $v['last_login_time']>0?date('Y-m-d H:i:s',$v['last_login_time']):'';
                $list[$k]['status_name'] = $v['status']==1?'正常':'禁用';
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['admin-user/edit','id'=>$v['id'],'sinKey'=>Encrypt::authcode('admin-'.$v['id'],'ENCODE')]);
                $list[$k]['changeUrl'] = yii::$app->urlManager->createUrl(['admin-user/change','id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
    }

    /**
     * 改变 管理员状态
     */
    public function actionChange(){
        $error['status'] = 0;
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $sinKey = isset($_GET['sinKey']) ? $_GET['sinKey'] : '';
        if(!$id || empty($sinKey) || 'change-'.$id != Encrypt::authcode($sinKey,'DECODE')){
            $error['info'] = '安全秘钥验证失败';
            $this->Json($error);
        }

        $models = Admin::findOne($id);
        if(! $models){
            $error['info'] = 'ID验证错误';
            $this->Json($error);
        }
        $models->status = $models->status == 1 ? 0 : 1;
        if($models->save()){
            $error['status'] = 1;
        }else{
            $error['info'] = '保存失败';
        }
        $this->Json($error);
    }

    /**
     * 新增管理员
     */
    public function actionAdd(){

        return $this->render('add');
    }

    /**
     * 编辑管理员
     */
    public function actionEdit(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $sinKey = isset($_GET['sinKey']) ? $_GET['sinKey'] : '';
        if(!$id || !$sinKey || 'admin-'.$id != Encrypt::authcode($sinKey,'DECODE')){
            throw new NotFoundHttpException('安全秘钥验证失败');
        }
        $models = Admin::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('edit',[
            'models' => $models
        ]);
    }

    /**
     * 保存 管理员
     */
    public function actionSave(){
        $params = yii::$app->request->post();

        //验证数据
        $dataInfo = $this->checkAdminUser($params);

        $models = New AdminUserSearch();
        $error = $models->saveAdminUser($dataInfo);

        $this->Json($error);
    }

    /**
     * 验证数据
     */
    public function checkAdminUser($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $sinKey = isset($_GET['sinKey']) ? $_GET['sinKey'] : '';

        $adminModeks = Admin::findOne($id);
        if($adminModeks){
            if('admin-'.$id != Encrypt::authcode($sinKey,'DECODE')){
                $error['info'] = '安全秘钥验证失败';
                $this->Json($error);
            }
            $params['id'] = $adminModeks->id;
            $params['username'] = $adminModeks->username;
        }

        //验证用户名
        if(!isset($params['username']) || empty($params['username'])){
            $error['info'] = '用户名不能为空';
            $this->Json($error);
        }
        if(!$adminModeks){
            if(Admin::findOne(['username'=>$params['username']])){
                $error['info'] = '用户名已存在';
                $this->Json($error);
            };
            //验证密码
            if(!isset($params['password']) || empty($params['password'])){
                $error['info'] = '密码不能为空';
                $this->Json($error);
            }
        }

        //权限组
        /*if(!isset($params['role_id']) || !intval($params['role_id'])){
            $error['info'] = '选择权限组';
            $this->Json($error);
        }*/

        return $params;
    }

    /**
     * 获取 权限下拉列表
     */
/*    public function actionGetRoleList(){
        $list = AdminRole::find()->orderBy('id ASC')->asArray()->all();
        if($list){
            $this->Json($list);
        }
        $this->Json([]);
    }*/
}