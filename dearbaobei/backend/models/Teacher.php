<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property string $name
 * @property string $bloc_id
 * @property string $school_id
 * @property string $structure_id
 * @property integer $sex
 * @property integer $duty_id
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property integer $status
 * @property string $create_time
 */
class Teacher extends \common\models\Teacher
{
    /**
     * 整理 Teacher表 插入 数据
     */
    public static  function teacherInit($dataInfo){
        $TeacherModels = new Teacher();

        $TeacherModels->name = $dataInfo['name'];
        $TeacherModels->bloc_id = $dataInfo['bloc_id'];
        $TeacherModels->sex = $dataInfo['sex'];
        $TeacherModels->school_id = $dataInfo['school_id'];
        $TeacherModels->structure_id = $dataInfo['structure_id'];
        $TeacherModels->duty_id = $dataInfo['duty_id'];
        $TeacherModels->status = 1;
        $TeacherModels->create_time = time();

        if( ! $TeacherModels->save()){
            throw new \Exception("Teacher表保存失败");
        }

        return $TeacherModels;
    }
}
