<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%grade_subject}}".
 *
 * @property string $grade_id
 * @property string $subject_id
 */
class GradeSubject extends \common\models\GradeSubject
{

    /**
     * 整理 grade_subject表 插入 数据
     */
    public static function setGradeSubjectInfoSave($SubjectSaveIds, $GradeSaveIds){
        $GradeSubjectIds = [];
        foreach($SubjectSaveIds as $k => $v){
            foreach($GradeSaveIds as $key => $val){
                $GradeSubjectModels = new \common\models\GradeSubject();
                $GradeSubjectModels->subject_id = $v;
                $GradeSubjectModels->grade_id = $val;

                if( ! $GradeSubjectModels->save() ){
                    throw new \Exception('保存grade_subject表 '.$k.'-'.$key.' 出错');
                }
                $GradeSubjectIds[] = $v.'_'.$val;
            }
        }

        return $GradeSubjectIds;
    }
}
