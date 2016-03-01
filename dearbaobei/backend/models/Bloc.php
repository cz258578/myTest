<?php

namespace backend\models;

use frontend\models\BlocStructure;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use common\models\Area;
use common\models\City;
use common\models\Province;

/**
 * This is the model class for table "{{%bloc}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $school_limit_num
 * @property integer $status
 * @property integer $create_time
 */
class Bloc extends \common\models\Bloc
{
    /**
     * 获取集团列表
     */
    public function getBlocList($params){
        $name = isset($params['name']) ? trim($params['name']) : '';

        $adminUserId = isset($params['admin_user_id']) ? (int)$params['admin_user_id'] : 0;
        $agentId = isset($params['agent_id']) ? (int)$params['agent_id'] : 0;

        $provinceId = isset($params['province_id']) ? (int)$params['province_id'] : 0;
        $cityId = isset($params['city_id']) ? (int)$params['city_id'] : 0;
        $areaId = isset($params['area_id']) ? (int)$params['area_id'] : 0;

        $createTimeStart = isset($params['create_time_start']) ? strtotime($params['create_time_start']) : 0;
        $createTimeEnd = isset($params['create_time_end']) ? strtotime($params['create_time_end']) : 0;

        //搜索
        $where = '';
        $qparams = [];

        if($name){
            $where .= 'b.name LIKE "%'.$name.'%"';
        }

        if($adminUserId){
            $where .= ($where!=''?' AND ':'').' b.admin_user_id ='.$adminUserId;
        }

        if($agentId){
            $where .= ($where!=''?' AND ':'').' b.agent_id ='.$agentId;
        }

        if($provinceId){
            $where .= ($where!=''?' AND ':'').' b.province_id ='.$provinceId;
        }

        if($cityId){
            $where .= ($where!=''?' AND ':'').' b.city_id ='.$cityId;
        }

        if($areaId){
            $where .= ($where!=''?' AND ':'').' b.area_id ='.$areaId;
        }

        if($createTimeStart>0){
            $where .= ($where!=''?' AND ':'').' b.create_time >'.$createTimeStart;
        }

        if($createTimeEnd>0){
            $where .= ($where!=''?' AND ':'').' b.create_time <'.($createTimeEnd+24*3600);
        }

        //排序
        $orderBy = 'b.id DESC';
        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = Bloc::find()->from(Bloc::tableName().' as b')
            ->leftJoin(Province::tableName().' as p','b.province_id=p.province_id')
            ->leftJoin(City::tableName().' as c','b.city_id=c.city_id')
            ->leftJoin(Area::tableName().' as a','b.area_id=a.area_id')
            ->leftJoin(Admin::tableName().' as ad','b.admin_user_id=ad.id')
            ->select('b.*,p.province,c.city,a.area,ad.name as admin_user_name')
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
     * 保存集团
     */
    public function saveBloc($params){
        $error['status'] = 0;
        $id = isset($params['id']) ? (int)$params['id'] : 0;

        $models = Bloc::findOne($id);
        if(! $models){
            $models = new Bloc();
        }

        $models->status = $params['status']=='status'?$models->status:$params['status'];

        $models->name = $params['name'];
        $models->school_limit_num = $params['school_limit_num']=='num'?$models->school_limit_num:$params['school_limit_num'];
        $models->contact_phone = $params['contact_phone'];
        $models->contacts = $params['contacts'];
        $models->qq = $params['qq'];
        $models->weixin = $params['weixin'];
        $models->email = $params['email'];
        $models->province_id = $params['province_id'];
        $models->city_id = $params['city_id'];
        $models->area_id = $params['area_id'];
        $models->addr = $params['addr'];
        $models->sex = isset($params['sex']) ? $params['sex'] : 0;

        //保存 集团 组织机构中的集团名称 需要跟着一起修改
        if($id){
            //开启 事物
            $Transaction = yii::$app->db->beginTransaction();
            try{
                if($models->save()){
                    $BlocStructureModels = BlocStructure::findOne(['parent_id'=>0,'bloc_id'=>$models->id]);
                    $BlocStructureModels->name = $params['name'];
                    if($BlocStructureModels->save()){
                        $Transaction->commit();
                        $error['status'] = 1;
                        $error['info'] = '保存成功！';
                        return $error;
                    }
                }
                $Transaction->rollBack();
                $error['info'] = '保存失败';
                return $error;
            }catch (\Exception $e){
                $Transaction->rollBack();
                $error['info'] = '保存失败';
                return $error;
            }
            return $error;
        }

        if($models->save()){
            $error['status'] = 1;
            $error['info'] = '保存成功！';
        }else{
            $error['info'] = '保存失败';
        }

        return $error;
    }

    /**
     * 整理 bloc表 插入 数据
     */
    public static function blocInit($BlocBespeakModels){
        $BlocModels = new Bloc();

        $BlocModels->name = $BlocBespeakModels['bloc_name'];
        $BlocModels->status = 1;
        $BlocModels->create_time = time();
        $BlocModels->weixin = $BlocBespeakModels['weixin'];
        $BlocModels->qq = $BlocBespeakModels['qq'];
        $BlocModels->email = $BlocBespeakModels['email'];
        $BlocModels->contact_phone = $BlocBespeakModels['contact_phone'];
        $BlocModels->contacts = $BlocBespeakModels['contacts'];
        $BlocModels->bespeak_id = $BlocBespeakModels['id'];
        $BlocModels->school_limit_num = yii::$app->params['schoolLimitNum'];
        $BlocModels->province_id = $BlocBespeakModels->province_id;
        $BlocModels->city_id = $BlocBespeakModels->city_id;
        $BlocModels->area_id = $BlocBespeakModels->area_id;
        $BlocModels->addr = $BlocBespeakModels->addr;
        $BlocModels->sex = $BlocBespeakModels->sex;
        $BlocModels->admin_user_id = $BlocBespeakModels->admin_user_id;

        if( ! $BlocModels->save()){
            throw new \Exception("bloc表保存失败");
        }
        return $BlocModels;
    }
}
