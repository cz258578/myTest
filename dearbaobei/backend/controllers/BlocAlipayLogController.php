<?php

namespace backend\controllers;

use backend\models\BlocAlipayLog;
use common\uitl\Encrypt;
use Yii;
use yii\web\NotFoundHttpException;

/**
* 管理员 控制器
*/
class BlocAlipayLogController extends AuthBaseController
{
	
    /**
    * 功能描述
    *
    */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 客户充值记录 首页 infojson
     */
    public function actionInfojson(){
        $params = yii::$app->request->post();

        $models = new BlocAlipayLog();
        $query = $models->getBlocAlipayLogList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){

                $list[$k]['status_name'] = yii::$app->params['blocOrderStatus'][$v['status']];

                $list[$k]['create_time_name'] = $v['create_time']>0?date('Y-m-d H:i:s',$v['create_time']):'';

            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
    }


    /**
     * 编辑集团
     */
    public function actionEdit(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = Bloc::findOne($id);
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
        $dataInfo = $this->checkBloc($params);

        $models = New Bloc();
        $error = $models->saveBloc($dataInfo);

        $this->Json($error);
    }

    /**
     * 验证数据
     */
    public function checkBloc($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if(! $id){
            $error['info'] = 'ID验证错误';
            $this->Json($error);
        }

        //验证用户名
        if(!isset($params['name']) || empty($params['name'])){
            $error['info'] = '集团不能为空';
            $this->Json($error);
        }


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