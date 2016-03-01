<?php

namespace backend\controllers;

use backend\models\Bloc;
use common\uitl\Encrypt;
use Yii;
use yii\web\NotFoundHttpException;

/**
* 管理员 控制器
*/
class BlocController extends AuthBaseController
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

        $models = new Bloc();
        $query = $models->getBlocList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){
                $list[$k]['sex_name'] = yii::$app->params['sexType'][$v['sex']];
                $list[$k]['addr_name'] = $v['province'].' '.$v['city'].' '.$v['area'].' '.$v['addr'];
                $list[$k]['create_time_name'] = $v['create_time']>0?date('Y-m-d H:i:s',$v['create_time']):'';
                $list[$k]['status_name'] = yii::$app->params['blocStatus'][$v['status']];;
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['bloc/edit','id'=>$v['id'],'sinKey'=>Encrypt::authcode('admin-'.$v['id'],'ENCODE')]);
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

        $models = new \frontend\models\BlocAccount();
        $dataInfo = $models->getBlocAccountInfo($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('edit',[
            'dataInfo' => $dataInfo
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