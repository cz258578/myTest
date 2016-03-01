<?php

namespace backend\models;

use Yii;
/**
 * This is the model class for table "{{%subject}}".
 *
 * @property string $id
 * @property string $name
 * @property string $school_id
 * @property string $sort
 * @property integer $status
 */
class Subject extends \common\models\Subject
{
    /**
     * 整理 subject表 插入 数据
     */
    public static function subjectInit($SchoolModels){
        $SubjectInit = yii::$app->params['blocSubjectInit'];
        $SubjectIds = [];
        foreach($SubjectInit as $k=>$v){
            $SubjectModels = new Subject();

            $SubjectModels->school_id = $SchoolModels['id'];
            $SubjectModels->status = 1;

            $SubjectModels->name = $v;
            $SubjectModels->sort = $k;

            if( ! $SubjectModels->save()){
                throw new \Exception("bloc表 ".$k." 保存失败");
            }
            $SubjectIds[$k] = $SubjectModels->id;
        }

        return $SubjectIds;
    }
}
