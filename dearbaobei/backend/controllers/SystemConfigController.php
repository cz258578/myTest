<?php

namespace backend\controllers;

use backend\models\SystemConfig;
use Yii;
use common\uitl\Encrypt;
use yii\web\NotFoundHttpException;

/**
* 系统配置 控制器
*/
class SystemConfigController extends AuthBaseController
{
	
    /**
    * 首页
    *
    */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * list json
     */
    public function actionInfojson(){

        $params = yii::$app->request->post();

        $models = new SystemConfig();
        $query = $models->getSystemConfigList($params);
        $list = $query->getModels();
        if($list){
            foreach($list as $k=>$v){
                $list[$k]['changeUrl'] = yii::$app->urlManager->createUrl(['system-config/change','id'=>$v['id']]);
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['system-config/edit','id'=>$v['id']]);
                $list[$k]['create_time_name'] = $v['create_time']>0?date('Y-m-d H:i:s',$v['create_time']):'';
                $list[$k]['value_name'] = $v['type']=='array'?implode(',',unserialize($v['value'])):$v['value'];
                $list[$k]['group_name'] = yii::$app->params['configGroup'][$v['group']];
            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
    }

    /**
     * 改变状态
     */
    public function actionChange(){
        $error['status'] = 0;
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $models = SystemConfig::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        $models->status = $models->status == 1 ? 0 : 1;

        if($models->save()){
            $error['status'] = 1;
            //更新 配置文件
            $this->setParamsFile();
        }else{
            $error['info'] = $models->getError();
        }
        $this->Json($error);
    }

    /**
     * 添加 系统配置
     */
    public function actionAdd(){

        return $this->render('add');
    }

    /**
     * 编辑 系统配置
     */
    public function actionEdit(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = SystemConfig::findOne($id);
        if( ! $models)
        {
            throw new NotFoundHttpException();
        }

        $value = '';
        if($models->type=='array'){
            $arr = unserialize($models->value);
            $i = 1;
            foreach($arr as $k=>$v){
                $value .= $k."=>".$v.($i<count($arr)?",":"")."\n";
                $i++;
            }
        }else{
            $value = $models->value;
        }

        return $this->render('edit',[
            'models' => $models,
            'value' => $value
        ]);
    }


    /*
     * 保存 系统配置
     */
    public function actionSave(){
        $params = yii::$app->request->post();

        //数据验证
        $dataInfo = $this->checkSystemConfig($params);

        $models = new SystemConfig();
        $error = $models->saveSystemConfig($dataInfo);

        //更新 配置文件
        if($error['status'] == 1){
            $this->setParamsFile();
        }
        $this->Json($error);
    }

    /**
     * 验证 系统配置 数据
     */
    public function checkSystemConfig($params){
        $error['status'] = 0;

        //验证字段名
        if(!isset($params['name']) || empty($params['name'])){
            $error['info'] = '字段名不能为空';
            $this->Json($error);
        }

        if(!intval($params['id']) && SystemConfig::findOne(['name'=>$params['name']])){
            $error['info'] = '字段名已存在';
            $this->Json($error);
        };

        //验证分组
        if(!isset($params['group']) || !intval($params['group'])){
            $error['info'] = '选择分组';
            $this->Json($error);
        }
        //类型
        if(!isset($params['type']) || empty($params['type'])){
            $error['info'] = '选择类型';
            $this->Json($error);
        }
        //值
        if(!isset($params['value']) || empty($params['value'])){
            $error['info'] = '值不能为空';
            $this->Json($error);
        }
        if($params['type'] == 'array'){
            $value = [];
            $arr = explode(',',trim($params['value']));
            foreach($arr as $v){
                $arr2 = explode('=>',$v);
                $value[trim($arr2[0])] = trim($arr2[1]);
            }
            $params['value'] = serialize($value);
        }

        return $params;
    }

    /**
     * 更新 配置文件
     */
    public function setParamsFile(){
        $path = yii::$app->basePath.'/../common/config/params.php';
        $info = '';
        $list = SystemConfig::find()->where(['status'=>1])->asArray()->all();

        $info .= '<?php';
        $info .= "\n\t".'return [';
        if($list){
            $t = 0;
            foreach($list as $k=>$v){
                $info .= "\n\t\t";
                $info .= '//'.$v['describe'];
                $info .= "\n\t\t";
                $info .= "'".$v['name']."'=>";
                if($v['type'] == 'array'){
                    $info .= "[";
                    $arr = unserialize($v['value']);
                    $i = 1;
                    foreach($arr as $key=>$val){
                        $info .= "\n\t\t\t";
                        $info .= "'".$key."'=>'".$val."'".($i<count($arr)?',':'');
                        $i++;
                    }
                    $info .= "\n\t\t";
                    $info .= "]";
                }elseif($v['type'] == 'string'){
                    $info .= "'".$v['value']."'";
                }
                $info .= $t<count($list)?',':'';
                $t++;
            }
        }
        $info .= "\n\t".'];';

        file_put_contents($path,$info);
    }
}