<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%teacher_duty}}".
 *
 * @property string $id
 * @property string $duty_name
 * @property string $bloc_id
 * @property string $sort
 * @property integer $status
 * @property string $creat_time
 */
class TeacherDuty extends \common\models\TeacherDuty
{
    /**
     * 整理 teacher_duty表 插入 数据
     */
    public static function teacherDutyInit($BlocModels){
        $BlocTeacherDutyInit = yii::$app->params['blocTeacherDutyInit'];
        $TeacherDutyIds = [];
        foreach($BlocTeacherDutyInit as $k=>$v){
            $TeacherDutyModels = new TeacherDuty();

            $TeacherDutyModels->bloc_id = $BlocModels['id'];
            $TeacherDutyModels->status = 1;

            $TeacherDutyModels->duty_name = $v;
            $TeacherDutyModels->sort = $k;

            if( ! $TeacherDutyModels->save()){
                throw new \Exception("bloc表 ".$k." 保存失败");
            }
            $TeacherDutyIds[$k] = $TeacherDutyModels->id;
        }

        return $TeacherDutyIds;
    }
}
