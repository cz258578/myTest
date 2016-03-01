<?php

namespace backend\models;

use common\models\Area;
use common\models\City;
use common\models\Province;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%bloc_bespeak}}".
 *
 * @property string $id
 * @property string $password
 * @property string $bloc_name
 * @property integer $sex
 * @property string $addr
 * @property string $contacts
 * @property string $contact_phone
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property integer $access_to
 * @property integer $admin_user_id
 * @property integer $intention_type
 * @property string $next_visit_time
 * @property string $note
 * @property integer $status
 * @property string $create_time
 */
class BlocBespeak extends \common\models\BlocBespeak
{
    /**
     * 获取预约列表
     */
    public function getAdminRoleList($params){
        $blocName = isset($params['bloc_name']) ? trim($params['bloc_name']) : '';

        $createTimeStart = isset($params['create_time_start']) ? strtotime($params['create_time_start']) : 0;
        $createTimeEnd = isset($params['create_time_end']) ? strtotime($params['create_time_end']) : 0;

        $status = isset($params['status']) ? (int)$params['status'] : 0;

        //搜索
        $where = '';
        $qparams = [];

        if($blocName){
            $where .= 'bb.bloc_name LIKE "%'.$blocName.'%"';
        }

        if($createTimeStart > 0){
            $where .= ($where!=''?' AND ':'').' bb.create_time > '.$createTimeStart;
        }

        if($createTimeEnd > 0){
            $where .= ($where!=''?' AND ':'').' bb.create_time < '.($createTimeEnd+3600*24);
        }

        if($status){
            $where .= ($where!=''?' AND ':'').' bb.status = '.$status;
        }

        //排序
        $orderBy = 'bb.status DESC,bb.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = BlocBespeak::find()->from(BlocBespeak::tableName().' as bb')
            ->leftJoin(Province::tableName().' as p','bb.province_id=p.province_id')
            ->leftJoin(City::tableName().' as c','bb.city_id=c.city_id')
            ->leftJoin(Area::tableName().' as a','bb.area_id=a.area_id')
            ->leftJoin(Admin::tableName().' as ad','bb.admin_user_id=ad.id')
            ->select('bb.*,p.province,c.city,a.area,ad.name as admin_user_name')
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
     * 保存 集团预约
     */
    public function saveBlocBespeak($params){

        //数据验证
        $params = $this->checkBlocBespeakInfo($params);
        if(count($params)==2){
            return $params;
        }

        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $models = BlocBespeak::findOne($id);
        if( ! $models){
            $models = new BlocBespeak();
            $models->create_time = time();
            $models->status = 3;

        }

        if($params['password']){
            $models->password = Admin::encryptPwd($params['password']) ;
        }
        $models->bloc_name = $params['bloc_name'];
        $models->addr = $params['addr'];
        $models->contact_phone = $params['contact_phone'];
        $models->contacts = $params['contacts'];
        $models->province_id = $params['province_id'];
        $models->city_id = $params['city_id'];
        $models->area_id = $params['area_id'];
        $models->access_to = $params['access_to'];
        $models->admin_user_id = $models->admin_user_id?$models->admin_user_id:$params['admin_user_id'];
        $models->intention_type = $params['intention_type'];
        $models->next_visit_time = $params['next_visit_time'];
        $models->note = $params['note'];
        $models->agent_id = $params['agent_id'];
        $models->qq = $params['qq'];
        $models->weixin = $params['weixin'];
        $models->email = $params['email'];
        $models->sex = $params['sex'];

        if($models->save()){
            $error['status'] = 1;
            $error['info'] = '提交成功';
        }else{
            $error['info'] = '提交失败！请刷新后重试。';
        }

        return $error;
    }


    /**
     * 验证 集团预约数据
     */
    public function checkBlocBespeakInfo($params){
        $error['status'] = 0;
        $dataInfo = [];

        $dataInfo['id'] = isset($params['id']) ? (int)$params['id'] : 0;
        //验证 名称
        $dataInfo['bloc_name'] = isset($params['bloc_name']) ? $params['bloc_name'] : '';
        if(empty($dataInfo['bloc_name'])){
            $error['info'] = '请填写名称';
            return $error;
        }
        //验证省市
        $dataInfo['province_id'] = isset($params['province_id']) ? (int)$params['province_id'] : 0;
        $dataInfo['city_id'] = isset($params['city_id']) ? (int)$params['city_id'] : 0;
        $dataInfo['area_id'] = isset($params['area_id']) ? (int)$params['area_id'] : 0;
        if(!$dataInfo['province_id'] || !$dataInfo['city_id'] || !$dataInfo['area_id']){
            $error['info'] = '请选择所在省市';
            return $error;
        }
        //详细地址
        $dataInfo['addr'] = isset($params['addr']) ? $params['addr'] : '';
        if(empty($dataInfo['addr'])){
            $error['info'] = '请填写详细地址';
            return $error;
        }
        //联系人
        $dataInfo['contacts'] = isset($params['contacts']) ? $params['contacts'] : '';
        if(empty($dataInfo['contacts'])){
            $error['info'] = '请填写联系人';
            return $error;
        }
        //联系手机
        $dataInfo['contact_phone'] = isset($params['contact_phone']) ? $params['contact_phone'] : '';
        if(empty($dataInfo['contact_phone'])){
            $error['info'] = '请填写正确联系手机';
            return $error;
        }
        //查询 用户表

        if((int)$params['id']){

        }else{
            $userModdels = User::find()->where('status = 1 AND phone=:phone AND teacher_id > 0')
                ->params([':phone'=>$dataInfo['contact_phone']])->count();
            if($userModdels>0){
                $error['info'] = '手机号码已存在';
                return $error;
            }
        }

        //密码
        $dataInfo['password'] = isset($params['password']) ? $params['password'] : '';
        if(!$dataInfo['id'] && empty($dataInfo['password'])){
            $error['info'] = '请填写密码';
            return $error;
        }

        $dataInfo['access_to'] = isset($params['access_to']) ? (int)$params['access_to'] : 0;
        $dataInfo['admin_user_id'] = isset($params['admin_user_id'])  ? $params['admin_user_id'] : 0;
        $dataInfo['intention_type'] = isset($params['intention_type'])  ? (int)$params['intention_type'] : 0;
        $dataInfo['next_visit_time'] = isset($params['next_visit_time'])  ? (int)strtotime($params['next_visit_time']) : 0;
        $dataInfo['note'] = isset($params['note']) ? $params['note'] : '';
        $dataInfo['agent_id'] = isset($params['agent_id']) ? (int)$params['agent_id'] : 0;
        $dataInfo['qq'] = isset($params['qq']) ? $params['qq'] : '';
        $dataInfo['email'] = isset($params['email']) ? $params['email'] : '';
        $dataInfo['weixin'] = isset($params['weixin']) ? $params['weixin'] : '';
        $dataInfo['sex'] = isset($params['sex']) ? $params['sex'] : 0;

        return $dataInfo;
    }
}
