<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\BlocBespeak;
use backend\models\BlocBespeakMemo;
use common\models\User;
use common\models\UserRole;
use common\models\UserRoleRelation;
use common\uitl\Encrypt;
use frontend\models\BlocStructure;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
* 管理员 功能模块
*/
class BlocBespeakController extends AuthBaseController
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

        $models = new BlocBespeak();
        $query = $models->getAdminRoleList($params);
        $list = $query->getModels();

        if($list){
            foreach($list as $k=>$v){
                $list[$k]['admin_user_name'] = $v['admin_user_name']?$v['admin_user_name']:'';
                $list[$k]['status_name'] = $v['status']>0?yii::$app->params['blocBespeakStatus'][$v['status']]:'';
                $list[$k]['address'] = $v['province'].' '.$v['city'].' '.$v['area'].' '.$v['addr'];
                $list[$k]['create_time_name'] = $v['create_time']>0?date('Y-m-d H:i:s',$v['create_time']):'';
                $list[$k]['intention_type_name'] = $v['intention_type']>0?yii::$app->params['blocBespeakIntentionType'][$v['intention_type']]:'';
                $list[$k]['next_visit_time_name'] = $v['next_visit_time']>0?date('Y-m-d H:i:s',$v['next_visit_time']):'';
                $list[$k]['access_to_name'] = $v['access_to']>0?yii::$app->params['accessWay'][$v['access_to']]:'';
                $list[$k]['editUrl'] = yii::$app->urlManager->createUrl(['bloc-bespeak/edit','id'=>$v['id'],'sinKey'=>Encrypt::authcode('bespeak-'.$v['id'],'ENCODE')]);
                $list[$k]['changeUrl'] = yii::$app->urlManager->createUrl(['bloc-bespeak/change','id'=>$v['id'],'sinKey'=>Encrypt::authcode('change-'.$v['id'],'ENCODE')]);
                $list[$k]['memoUrl'] = yii::$app->urlManager->createUrl(['bloc-bespeak/get-bespeak-memo','bespeak_id'=>$v['id'],'sinKey'=>Encrypt::authcode('memo-'.$v['id'],'ENCODE')]);
            }
            $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
        }
        $this->Json([]);
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

        $models = BlocBespeak::findOne($id);

        $AdminModels = Admin::findOne($models->admin_user_id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('edit',[
            'models' => $models,
            'adminModels'=>$AdminModels
        ]);
    }

    /**
     * 保存 管理员功能模块
     */
    public function actionSave(){
        $params = yii::$app->request->post();

        $models = New BlocBespeak();
        $error = $models->saveBlocBespeak($params);

        $this->Json($error);
    }

    /**
     * 审批
     */
    public function actionChange(){
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $models = BlocBespeak::findOne($id);
        if(! $models){
            throw new NotFoundHttpException('ID验证失败');
        }

        return $this->render('change',[
            'models' => $models
        ]);
    }

    /**
     * 保存审批
     */
    public function actionSaveChange(){
        $error['status'] = 0;
        $params = yii::$app->request->post();
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $status = isset($params['status']) ? (int)$params['status'] : 0;

        $BlocBespeakModels = BlocBespeak::findOne($id);

        if(! $BlocBespeakModels){
            $error['info'] = 'ID验证错误';
            $this->Json($error);
        }

        //验证手机号码 在用户表中是否存在
        $checkUserModels = \common\models\User::find()->where('phone = '.$BlocBespeakModels->contact_phone.' AND status=1 AND teacher_id>0')->count();
        if($checkUserModels){
            $error['info'] = '此手机号码已经存在';
            $this->Json($error);
        }

        //待审批
        if($status == 3){
            $error['status'] = 1;
            $error['info'] = '保存成功';
            $this->Json($error);
        }

        //验证 status
        if($status == 1){ //未通过
            $BlocBespeakModels->status = $status;
            if($BlocBespeakModels->save()){
                $error['status'] = 1;
                $error['info'] = '保存成功';
            }else{
                $error['info'] = '保存失败';
            }
            $this->Json($error);
        }elseif($status == 2){ //已注册 审核通过
        //开启事物
         $Transaction = yii::$app->db->beginTransaction();
             try{
                $BlocBespeakModels->status = $status;
                if($BlocBespeakModels->save()){
                    //整理 新建 bloc表 数据
                    $BlocModels = \backend\models\Bloc::blocInit($BlocBespeakModels);

                    //整理 新建 bloc_account表 数据
                    $BlocAccountModels = \backend\models\BlocAccount::blocAccountInit($BlocModels);

                    //整理 新建 bloc_structure表 数据
                    $BlocStructureModels = \backend\models\BlocStructure::blocStructureInit($BlocModels);


                    //整理 新建 teacher_duty表 数据
                    $TeacherDutyModels = \backend\models\TeacherDuty::teacherDutyInit($BlocModels);

                    //整理 新建 user_role表 数据 1条 全局管理
                    $UserRoleSaveIds = $this->setUserRoleInfoSave($BlocModels);

                    //整理 新建 school表 数据
                    $schoolInfo['blocId'] = $BlocModels->id;
                    $schoolInfo['blocName'] = $BlocModels->name;
                    $schoolInfo['blocAddr'] = $BlocBespeakModels->addr;
                    $schoolInfo['provinceId'] = $BlocBespeakModels->province_id;
                    $schoolInfo['cityId'] = $BlocBespeakModels->city_id;
                    $schoolInfo['areaId'] = $BlocBespeakModels->area_id;
                    $SchoolModels = \backend\models\School::schoolInit($schoolInfo);

                    //整理 新建 teacher表 数据
                    $TeaStructure = BlocStructure::find()->where('school_id='.$SchoolModels->id)->all();
                    foreach($TeaStructure as $k=>$v){
                        if($v['name']=='教学部'){
                            $structure_id = $v['id'];
                        }
                    }
                    if(!isset($structure_id)){
                        throw new \Exception('初始化分配教学部失败');
                    }
                    foreach(yii::$app->params['blocTeacherDutyInit'] as $k=>$v){
                        if($v=='教师'){
                            $duty_id = $TeacherDutyModels[$k];
                        }
                    }
                    if(!isset($duty_id)){
                        throw new \Exception('初始化分配职务失败');
                    }
                    $teacherInfo['name'] = $BlocBespeakModels->contacts;
                    $teacherInfo['bloc_id'] = $BlocModels->id;
                    $teacherInfo['sex'] = $BlocBespeakModels->sex;
                    $teacherInfo['school_id'] = $SchoolModels->id;
                    $teacherInfo['structure_id'] = $structure_id;
                    $teacherInfo['duty_id'] = $duty_id;
                    $TeacherModels = \backend\models\Teacher::teacherInit($teacherInfo);

                    //整理 新建 subject表 数据
                    $SubjectSaveIds = \backend\models\Subject::subjectInit($SchoolModels);

                    //整理 新建 grade表 数据
                    $GradeSaveIds = \backend\models\Grade::gradeInit($SchoolModels);

                    //整理 新建 grade_subject表 数据
                    $GradeSubject = \backend\models\GradeSubject::setGradeSubjectInfoSave($SubjectSaveIds,$GradeSaveIds);

                    //整理 新建 user表 数据
                    $userInfo['name'] = $BlocBespeakModels->contacts;
                    /*$userInfo['qq'] = $BlocBespeakModels->qq;
                    $userInfo['email'] = $BlocBespeakModels->email;
                    $userInfo['weixin'] = $BlocBespeakModels->weixin;*/
                    $userInfo['phone'] = $BlocBespeakModels->contact_phone;
                    $userInfo['password'] = $BlocBespeakModels->password;
                    $userInfo['teacher_id'] = $TeacherModels->id;
                    $UserModels = \backend\models\User::userInit($userInfo);

                    //整理 新建  user_role_relation表 数据
                    $UserRoleRelationModels = $this->setUserRoleRelationInfoSave($UserRoleSaveIds,$UserModels,$BlocModels);

                    if($BlocModels && $BlocAccountModels && $TeacherModels && $TeacherDutyModels && $SchoolModels && $GradeSaveIds
                        && $SubjectSaveIds && $GradeSubject && $UserModels && $UserRoleSaveIds && $UserRoleRelationModels && $BlocStructureModels){
                        //提交数据
                        $Transaction->commit();
                        $error['status'] = 1;
                        $error['info'] = '保存成功';
                        $this->Json($error);
                    }
                }
                 //回滚 保存失败
                 $Transaction->rollBack();
                 $error['info'] = '保存出错1';
                 $this->Json($error);
             }catch (\Exception $e){
                 //回滚
                 $Transaction->rollBack();
                 $str = '错误信息：'.$e->getMessage();
                 $str .= '错误文件：'.$e->getFile();
                 $str .= '行数：'.$e->getLine();
                 $error['info'] = $str;
                 $this->Json($error);
             }
        }
    }

    /**
     * 整理 user_role_relation表 插入 数据
     */
    public function setUserRoleRelationInfoSave($UserRoleSaveIds,$UserModels,$BlocModels){
        $UserRoleRelationModels = new UserRoleRelation();

        $UserRoleRelationModels->role_id = $UserRoleSaveIds[0];
        $UserRoleRelationModels->bloc_id = $BlocModels->id;
        $UserRoleRelationModels->school_id = 0;
        $UserRoleRelationModels->class_id = 0;
        $UserRoleRelationModels->user_id = $UserModels->id;
        $UserRoleRelationModels->is_master = 1;

        if( ! $UserRoleRelationModels->save()){
            throw new \Exception("保存user_role_relation表失败");
        }

        return $UserRoleRelationModels;
    }

    /**
     * 整理 user_role表 插入 数据
     * 需要插入 1条 初始数据 全局管理
     */
    public function setUserRoleInfoSave($BlocModels){
        $dataInfo = [];
        //全局管理 权限
        $dataInfo[0]['name'] = '全局管理员';
        $dataInfo[0]['bloc_id'] = $BlocModels->id;
        //查询 所有 modules
        $modulesModels = \common\models\UserModules::find()->where(['status'=>1])->asArray()->all();
        if(!$modulesModels){
            throw new \Exception("查询 UserModules表 失败");
        }
        $modules_ids = [];
        foreach($modulesModels as $v){
            $modules_ids[] = $v['id'];
        }
        $dataInfo[0]['modules_ids'] = ','.implode(',',$modules_ids).',';
        $dataInfo[0]['type'] = 0;

/*        //学校管理 权限
        $dataInfo[1]['name'] = '学校管理员';
        $dataInfo[1]['bloc_id'] = $BlocModels->id;
        $dataInfo[1]['modules_ids'] = yii::$app->params['userRoleSchoolArr'];
        $dataInfo[1]['type'] = 1;

        //班级管理 权限
        $dataInfo[2]['name'] = '班级管理员';
        $dataInfo[2]['bloc_id'] = $BlocModels->id;
        $dataInfo[2]['modules_ids'] = yii::$app->params['userRoleClassArr'];
        $dataInfo[2]['type'] = 2;*/

        $UserRoleSaveIds = [];
        foreach($dataInfo as $k=>$v){
            $UserRoleModels = new UserRole();

            $UserRoleModels->name = $v['name'];
            $UserRoleModels->bloc_id = $v['bloc_id'];
            $UserRoleModels->modules_ids = $v['modules_ids'];
            $UserRoleModels->type = $v['type'];

            if( ! $UserRoleModels->save()){
                throw new \Exception("user_role表 ".$k." 保存失败");
            }
            //记录保存ID
            $UserRoleSaveIds[$k] = $UserRoleModels->id;
        }

        return $UserRoleSaveIds;
    }

    /**
     * 获取 集团预约 跟进 list
     */
    public function actionGetBespeakMemo(){
        $params = [];
        $bespeak_id = isset($_GET['bespeak_id']) ? (int)$_GET['bespeak_id'] : 0;
        //$sinKey = isset($_GET['sinKey']) ? $_GET['sinKey'] : '';
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
        $rows = isset($_POST['rows']) ? (int)$_POST['rows'] : 5;

        if($bespeak_id){
            $params['bespeak_id'] = $bespeak_id;
            $params['page'] = $page;
            $params['rows'] = $rows;

            $models = new BlocBespeakMemo();
            $query = $models->getBespeakMemoList($params);
            $list = $query->getModels();

            if($list){
                foreach($list as $k=>$v){
                    $list[$k]['create_time_name'] = date('Y-m-d H:i:s',$v['create_time']);
                }
                $this->Json(['total'=>$query->totalCount,'rows'=>$list]);
            }
        }
        $this->Json([]);
    }

    /**
     * 保存 预约跟进
     */
    public function actionSaveBespeakMemo(){
        $error['status'] = 0;
        $params = yii::$app->request->post();
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        //$sinKeyMemo = isset($params['sinKeyMemo']) ? $params['sinKeyMemo'] : '';
        $desc = isset($params['desc']) ? $params['desc'] : '';
        if(empty($desc)){
            $error['info'] = '请填写内容';
            $this->Json($error);
        }

        if($id){
            $models = new BlocBespeakMemo();
            $models->bespeak_id = $id;
            $models->admin_user_id = yii::$app->user->identity->id;
            $models->description = $desc;
            $models->create_time = time();
            if( $models->save() ){
                $error['status'] = 1;
                $error['info'] = '保存成功';
                $this->Json($error);
            }
            //$error['info'] = $models->getErrors();
            $error['info'] = '保存失败';
            $this->Json($error);
        }
        $error['info'] = 'ID验证错误';
        $this->Json($error);
    }
}