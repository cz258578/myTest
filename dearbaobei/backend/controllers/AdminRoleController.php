<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminModules;
use backend\models\AdminRole;
use backend\models\AdminUserSearch;
use common\uitl\Encrypt;
use Yii;
use yii\web\NotFoundHttpException;

/**
* 角色权限 功能模块
*/
class AdminRoleController extends AuthBaseController
{
	
    /**
    * 功角色权限
    *
    */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 角色权限 首页 infojson
     */
    public function actionInfojson(){
        $params = yii::$app->request->post();

        $models = new AdminRole();
        $query = $models->getAdminRoleList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){
                //$list[$k]['status_name'] = $v['status']==1?'正常':'禁用';
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['admin-role/edit','id'=>$v['id'],'sinKey'=>Encrypt::authcode('admin-'.$v['id'],'ENCODE')]);
                $list[$k]['changeUrl'] = yii::$app->urlManager->createUrl(['admin-role/change','id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
                $list[$k]['setRoleUrl'] = yii::$app->urlManager->createUrl(['admin-role/role','id'=>$v['id'],'sinKey'=>Encrypt::authcode('admin-'.$v['id'],'ENCODE')]);
                $list[$k]['setRoleUserUrl'] = yii::$app->urlManager->createUrl(['admin-role/role-user','id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
    }

    /**
     * 新增管理员角色权限
     */
    public function actionAdd(){

        return $this->render('add');
    }

    /**
     * 编辑角色权限功能模块
     */
    public function actionEdit(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = AdminRole::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('edit',[
            'models' => $models
        ]);
    }

    /**
     * 保存 角色权限功能模块
     */
    public function actionSave(){
        $params = yii::$app->request->post();

        //验证数据
        $dataInfo = $this->checkAdminRole($params);

        $models = New AdminRole();
        $error = $models->saveAdminRole($dataInfo);

        $this->Json($error);
    }

    /**
     * 验证数据功能模块
     */
    public function checkAdminRole($params){
        $error['status'] = 0;

        //验证
        if(isset($params['name']) && empty($params['name'])){
            $error['info'] = '名称不能为空';
            $this->Json($error);
        }

        //验证
        if(isset($params['modules_ids']) && empty($params['modules_ids'])){
            $error['info'] = '选择权限';
            $this->Json($error);
        }

        if(empty($params['name']) && empty($params['modules_ids'])){
            $error['info'] = '数据错误';
            $this->Json($error);
        }

        return $params;
    }

    /**
     * 权限管理
     */
    public function actionRole(){

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = AdminRole::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        $html = $this->getModulesArrayForHtml(0,$models->modules_ids);

        return $this->render('role',[
            'html' => $html,
            'models' => $models
        ]);
    }

    /**
     * getModulesArrayForHtml
     */
    public function getModulesArrayForHtml($pid=0,$arrTrue=[]){
        $result = '';
        $modulesModels = AdminModules::find()->where(['status'=>1,'parent_id'=>$pid])->orderBy('parent_str ASC')->asArray()->all();
        if($modulesModels){
            foreach($modulesModels as $v){
                $checked = in_array($v['id'],explode(',',$arrTrue))?'checked=true':'';
                $class = $pid==0?'class="roleCheckLabelOne"':(count(explode(',',$v['parent_str']))==3?'class="roleCheckLabelTwo"':'');
                $result .= $pid==0?'<div class="roleCheckOne">':(count(explode(',',$v['parent_str']))==3?'<div class="roleCheckTwo">':'<span>');
                $result .= '<label '.$class.'><input type="checkbox" '.$checked.' class="modules_ids" name="modules_ids[]" value="'.$v['id'].'">'.$v['name'].'</label>';
                $result .= $this->getModulesArrayForHtml($v['id'],$arrTrue);
                $result .= $pid==0?'</div>':(count(explode(',',$v['parent_str']))==3?'</div>':'</span>');
            }
        }
        return $result;
    }

    /**
     * 保存 角色权限
     */


    /**
     * 成员管理
     */
    public function actionRoleUser(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $models = AdminRole::findOne($id);
        if( ! $models){
          throw new NotFoundHttpException('ID验证错误');
        }

        return $this->render('role-user',[
            'models' => $models
        ]);
    }

    /**
     * 用户 列表
     */
    public function actionGetRoleUserList(){
        $params = yii::$app->request->queryParams;

        $models = new AdminUserSearch();
        $query = $models->getAdminUserList($params);
        $list = $query->getModels();

        if($list){

            $this->Json($list);
        }
        $this->Json([]);
    }

    /**
     * 用户 权限保存
     */
    public function actionSaveRoleUser(){
        $error['status'] = 0;
        $id = isset($_POST['uid']) ? (int)$_POST['uid'] : 0;
        $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 0;
        $models = Admin::findOne($id);
        if( ! $models){
            $error['info'] = 'ID验证错误';
            $this->Json($error);
        }
        $models->role_id = $role_id;
        if($models->save()){
            $error['status'] = 1;
            $error['info'] = '保存成功';
        }else{
            $error['info'] = $models->getError();
        }
        $this->Json($error);
    }
}