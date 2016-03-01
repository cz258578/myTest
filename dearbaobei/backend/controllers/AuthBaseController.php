<?php
namespace backend\controllers;

use backend\models\AdminModules;
use backend\models\AdminRole;
use Yii;
use yii\web\NotFoundHttpException;

/**   
* 权限基础控制器  
*   
* 为该应用提供需要权限验证基础的控制服务
* 
* @author weinengyu   
* 
*/
class AuthBaseController extends BaseController
{
    static public $upImageUrl = '/uploads/photo/';
    /**
     * 不需权限认证的controller
     */
    public $excludeCtrls = ['Base', 'BaseBootstrap', 'Site', 'default', 'Upload'];
    /**
     * 不需权限认证的action
     */
    public $excludeActions = ['ajax', 'myaccount', 'showerror', 'showsuccess'];

    /**
     * 定义 可访问的 modules_ids
     */
    public $adminUserRoleModulesIds = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            //'access' => [
            //    'class' => AccessControl::className(),
            //    'rules' => ['@']
            //],
            //'verbs' => [
            //    'class' => VerbFilter::className(),
            //    'actions' => [
            //        'delete' => ['post'],
            //    ],
            //],
        ];
    }

    public function init()
    {
         if (Yii::$app->user->isGuest)
         {
            $this->redirect(Yii::$app->user->loginUrl);
         }


    }
    
    public function beforeAction($action)
    {
        if($this->checkUserRole(yii::$app->user->identity->role_id,$action)){
            return true;
        }else{
            throw new NotFoundHttpException('没有权限访问');
        }
    }

    /**
     * 验证用户 权限
     */
    public function checkUserRole($role_id,$action){
        //查询 modules 菜单导航 需要
        $UserRoleArr = $this->selectModulesForRoleId($role_id);
        $this->adminUserRoleModulesIds = $UserRoleArr['models'];

        if($role_id==1){//超级管理 不需验证
            return true;
        }

        if(! $UserRoleArr){
            return false;
        }

        //当前 url
        $a_url = $this->action->controller->id.'/'.$action->id;

        if(in_array($a_url,$UserRoleArr['module'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询 modules
     */
    public function selectModulesForRoleId($role_id){
        $UserRoleArr = [];
        $where = '';
        if($role_id!=1){
            $RoleModels = AdminRole::findOne($role_id);
            if(! $RoleModels){
                return $UserRoleArr; //查不到数据 -_-
            }

            if(empty($RoleModels->modules_ids)){
                return $UserRoleArr; //没分配权限 -_-
            }

            $arr = explode(',',$RoleModels->modules_ids);
            if(count($arr)<3) return $UserRoleArr; // 判断下，别出错 ^_^

            $modulesIds = '';
            foreach($arr as $k=>$v){
                if($v){
                    $modulesIds .= ($k>1?',':'').$v;
                }
            }
            $where = 'id IN ('.$modulesIds.') AND status=1 ';
        }
        //查询 modules

        $ModulesModels = AdminModules::find()->where($where)->asArray()->all();

        foreach($ModulesModels as $v){
            $UserRoleArr['module'][] = $v['module_addr'].'/'.$v['action_addr'];
        }
        $UserRoleArr['models'] = $ModulesModels;
        return $UserRoleArr;
    }
}
