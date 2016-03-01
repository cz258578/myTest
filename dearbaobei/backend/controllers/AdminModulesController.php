<?php

namespace backend\controllers;

use backend\models\AdminModules;
use common\uitl\Encrypt;
use Yii;
use yii\web\NotFoundHttpException;

/**
* 管理员 功能模块
*/
class AdminModulesController extends AuthBaseController
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

        $models = new AdminModules();
        $query = $models->getAdminModulesList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){
                $list[$k]['create_time_name'] = $v['create_time']>0?date('Y-m-d H:i:s',$v['create_time']):'';
                $list[$k]['status_name'] = $v['status']==1?'正常':'禁用';
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['admin-modules/edit','id'=>$v['id'],'sinKey'=>Encrypt::authcode('admin-'.$v['id'],'ENCODE')]);
                $list[$k]['changeUrl'] = yii::$app->urlManager->createUrl(['admin-modules/change','id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
                $list[$k]['addUrl'] = yii::$app->urlManager->createUrl(['admin-modules/add','p_id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
    }

    /**
     * 改变 管理员功能模块 状态
     */
    public function actionChange(){
        $error['status'] = 0;
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = AdminModules::findOne($id);
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
     * 新增管理员功能模块
     */
    public function actionAdd(){

        return $this->render('add');
    }

    /**
     * 编辑管理员功能模块
     */
    public function actionEdit(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = AdminModules::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('edit',[
            'models' => $models
        ]);
    }

    /**
     * 保存 管理员功能模块
     */
    public function actionSave(){
        $params = yii::$app->request->post();

        //验证数据
        $dataInfo = $this->checkAdminModules($params);

        $models = New AdminModules();
        $error = $models->saveAdminModules($dataInfo);

        $this->Json($error);
    }

    /**
     * 验证数据功能模块
     */
    public function checkAdminModules($params){
        $error['status'] = 0;

        //验证
        if(!isset($params['name']) || empty($params['name'])){
            $error['info'] = '名称不能为空';
            $this->Json($error);
        }
        //控制器
        if(!isset($params['module_addr']) || empty($params['module_addr'])){
            $error['info'] = '控制器不能为空';
            $this->Json($error);
        }
        //方法
        if(!isset($params['action_addr']) || empty($params['action_addr'])){
            $error['info'] = '方法不能为空';
            $this->Json($error);
        }

        return $params;
    }

    /**
     * 获取父类list
     */
    public function actionGetParentList(){
        $parent_id = isset($_GET['p_id']) ? $_GET['p_id'] : 0;

        $models = AdminModules::find()->where(['status'=>1])->asArray()->all();
        foreach($models as $k=>$v){
            if($v['id'] == $parent_id){
                $models[$k]['checked'] = true;
            }
        }

        $parentList = \frontend\models\BlocStructure::getMultiArrayByList($models);

        $head = ['id'=>'0',
            'name'=>'顶级菜单'
        ];
        array_unshift($parentList,$head);

        $this->Json($parentList);
    }
}