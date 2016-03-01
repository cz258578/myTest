<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%school}}".
 *
 * @property string $id
 * @property string $name
 * @property string $first_termname
 * @property integer $first_term_month
 * @property string $last_termname
 * @property integer $last_term_month
 * @property integer $type
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property string $lng
 * @property string $lat
 * @property string $bloc_id
 * @property string $address
 * @property string $create_time
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * 初始化学校信息
     * @param blocArray array
     *
     * @return array
     */
	public static function schoolInit($blocArray)
	{
		$schoolModel = new School();
        $schoolModel->create_time = time();
        $schoolModel->order_start_time = time();
        $schoolModel->bloc_id = $blocArray['blocId'];

        $date = (date('Y') + 100) . '-' . date('m') . '-' . date('d') . ' 0:00:00'; // 截止时间韦100年

        $endTime = new \DateTime($date,  new \DateTimeZone('PRC'));
        $schoolModel->order_end_time = $endTime->format('U');

        /* 处理保存的字段 */
        $schoolModel->name = $blocArray['blocName']; // 集团名称 
        $schoolModel->first_termname = '春季学期'; // 第一个学期的名字
        $schoolModel->first_term_month = 3; // 第一个学期的开学月份
        $schoolModel->last_termname = '秋季学期'; // 第二个学期的名字
        $schoolModel->last_term_month = 9; // 第二个学期的开学月份
        $schoolModel->province_id = $blocArray['provinceId']; // 集团省份id
        $schoolModel->city_id = $blocArray['cityId'];  // 集团城市id
        $schoolModel->area_id = $blocArray['areaId']; // 集团区域id
       
        $schoolModel->address = $blocArray['blocAddr']; // 集团的详细地址
        $schoolModel->level = 1; // 默认的等级就是免费的

        /* 根据学校地址请求百度API记录经纬度信息 */        
        $city = \common\models\City::find()->where('city_id=:city_id')->params([':city_id' => $schoolModel->city_id])->one();
        $cityName = $city? $city->city: '';

        $schoolLocal = \common\uitl\UtilHelper::BaiduMapGeo($cityName, $schoolModel->address);

        $schoolModel->lng = isset($schoolLocal['lng'])? $schoolLocal['lng']: 0;
        $schoolModel->lat = isset($schoolLocal['lat'])? $schoolLocal['lat']: 0;         

        /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {

            if ( ! $schoolModel->save()) {
                $innerTransaction->rollBack();
                throw new \Exception("school table is fail"); 
            } else {

	             /* 如果是新增记录, 则往集团结构表里面加入学校的架构 */
	            $blocStructureModel = BlocStructure::find()->where('bloc_id=:bloc_id AND parent_id=0')->params([':bloc_id' => $blocArray['blocId']])->one();
	            if ($blocStructureModel) {                    
	                $newBlocStructureModel = new BlocStructure();
	                $newBlocStructureModel->name = $schoolModel->name;
	                $newBlocStructureModel->parent_id = $blocStructureModel->id;
	                $newBlocStructureModel->bloc_id = $blocStructureModel->bloc_id;
	                $newBlocStructureModel->school_id = $schoolModel->id;
	                $newBlocStructureModel->parent_str = ',' . $blocStructureModel->id . ',';
	                $newBlocStructureModel->create_time = time();
	                $newBlocStructureModel->save();

	                if ($newBlocStructureModel->save()) {
	                    $newBlocStructureModel->sort_id = $blocStructureModel->id;
	                    $newBlocStructureModel->save();
	                }
	            }

                //写入 初始的部门
                //获取 初始部门 数组
                $blocSchoolStureInit = yii::$app->params['blocSchoolStureInit'];
                foreach($blocSchoolStureInit as $k=>$v){
                    $blocSchoolStureModels = new BlocStructure();
                    $blocSchoolStureModels->name = $v;
                    $blocSchoolStureModels->parent_id = $newBlocStructureModel->id;
                    $blocSchoolStureModels->bloc_id = $newBlocStructureModel->bloc_id;
                    $blocSchoolStureModels->school_id = $newBlocStructureModel->school_id;
                    $blocSchoolStureModels->parent_str = $newBlocStructureModel->parent_str . $newBlocStructureModel->id . ',';
                    $blocSchoolStureModels->create_time = time();

                    if( ! $blocSchoolStureModels->save()){
                        throw new \Exception('blocSchoolStureInit 初始化失败 '.$k);
                    }
                    $blocSchoolStureModels->sort_id = $blocSchoolStureModels->id;
                    if( ! $blocSchoolStureModels->save()){
                        throw new \Exception('blocSchoolStureInit 排序 '.$k);
                    }
                }

	            /* 如果是新增记录, 则往角色表里面写一条记录 */

	            /* 学校角色管理 */
	            $userRole = new \common\models\UserRole();
	            $userRole->name = '学校管理员';
	            $userRole->bloc_id = $schoolModel->bloc_id;
	            $userRole->school_id = $schoolModel->id;
	            $userRole->bloc_id = $schoolModel->bloc_id;
	            $userRole->school_id = $schoolModel->id;
	            $userRole->note = '负责整个学校的管理工作';

	            $modulesIds = isset(yii::$app->params['userRoleSchoolArr'])?  yii::$app->params['userRoleSchoolArr']: ''; // 读取公共配置的学校默认权限

	            $userRole->modules_ids = $modulesIds;
	            $userRole->type = 1;

	            if ( ! $userRole->save()) {
	            	throw new \Exception("scchool user_role table is fail");
	            }

                /* 班级角色管理 */
                $classUserRole = new \common\models\UserRole();
                $classUserRole->name = '班级管理员';
                $classUserRole->bloc_id = $schoolModel->bloc_id;
                $classUserRole->school_id = $schoolModel->id;
                $classUserRole->note = '负责班级内的管理工作';

                $modulesIds = isset(yii::$app->params['userRoleClassArr'])?  yii::$app->params['userRoleClassArr']: ''; // 读取公共配置的班级默认权限

                $classUserRole->modules_ids = $modulesIds;
                $classUserRole->type = 2;

                if ( ! $classUserRole->save()) {
                    throw new \Exception("class user_role table is fail");
                }

	            /* 写入通用课程表模板 */
	            $scheduleModel = new \common\models\ScheduleTemplate();
	            $scheduleModel->week_type = 2; // 周末上课类型
	            $scheduleModel->morning_has_num = 3; // 上午有多少节课
	            $scheduleModel->afternoon_has_num = 3; // 下午有多少节课
	            $scheduleModel->duration = 45; // 下午有多少节课                
	            $scheduleModel->rest_duration = 10; // 课间休息时长                
	            $scheduleModel->school_id = $schoolModel->id; // 学校ID
	            $scheduleModel->grade_id = 0; // 年级ID
	            $scheduleModel->type = 2; // 1年级专用模板, 2学校通用模板
	            $scheduleModel->status = 1; // 状态
	            $scheduleModel->is_show = 1; // 是否显示

	            $scheduleModel->content = 'a:7:{i:0;a:4:{s:4:"sort";i:1;s:10:"start_time";s:5:"09:00";s:8:"end_time";s:5:"09:45";s:4:"type";i:1;}i:1;a:4:{s:4:"sort";i:2;s:10:"start_time";s:5:"09:55";s:8:"end_time";s:5:"10:40";s:4:"type";i:1;}i:2;a:4:{s:4:"sort";i:3;s:10:"start_time";s:5:"10:50";s:8:"end_time";s:5:"11:35";s:4:"type";i:1;}i:3;a:4:{s:4:"sort";s:0:"";s:10:"start_time";s:0:"";s:8:"end_time";s:0:"";s:4:"type";i:2;}i:4;a:4:{s:4:"sort";i:4;s:10:"start_time";s:5:"14:00";s:8:"end_time";s:5:"14:45";s:4:"type";i:1;}i:5;a:4:{s:4:"sort";i:5;s:10:"start_time";s:5:"14:55";s:8:"end_time";s:5:"15:40";s:4:"type";i:1;}i:6;a:4:{s:4:"sort";i:6;s:10:"start_time";s:5:"15:50";s:8:"end_time";s:5:"16:35";s:4:"type";i:1;}}';

	            if ( ! $scheduleModel->save()) {
	            	throw new \Exception("schedule table is fail");
	            }
            }

            $innerTransaction->commit();
        } catch (\Exception $e) {

            $innerTransaction->rollBack();
            throw new \Exception("school table is fail");
        }

        return $schoolModel;
	}
}
