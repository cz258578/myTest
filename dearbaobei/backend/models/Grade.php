<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%grade}}".
 *
 * @property string $id
 * @property string $name
 * @property string $school_id
 * @property string $sort
 * @property integer $is_end_grade
 */
class Grade extends \common\models\Grade
{
    /**
     * 整理 grade表 插入 数据
     */
    public static function gradeInit($SchoolModels){
        $GradeInit = yii::$app->params['blocGradeInit'];
        $GradeIds = [];
        foreach($GradeInit as $k=>$v){
            $GradeModels = new Grade();

            $GradeModels->school_id = $SchoolModels['id'];
            $GradeModels->status = 1;

            $GradeModels->name = $v;
            $GradeModels->sort = $k;

            $GradeModels->is_end_grade = $k==count($GradeInit) ? 1 : 0;

            if( ! $GradeModels->save()){
                throw new \Exception("bloc表 ".$k." 保存失败");
            }
            $GradeIds[$k] = $GradeModels->id;
        }

        return $GradeIds;
    }
}
