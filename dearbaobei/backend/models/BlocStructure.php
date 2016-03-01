<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%bloc_structure}}".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property string $bloc_id
 * @property string $school_id
 * @property string $parent_str
 * @property string $sort_id
 * @property string $create_time
 */
class BlocStructure extends \common\models\BlocStructure
{
    /**
     * 保存 集团 组织机构
     */
    public static function blocStructureInit($BlocModels){
        $BlocStructureModels = new BlocStructure();

        $BlocStructureModels->name = $BlocModels['name'];
        $BlocStructureModels->bloc_id = $BlocModels['id'];
        $BlocStructureModels->school_id = 0;
        $BlocStructureModels->parent_id = 0;
        $BlocStructureModels->parent_str = '';
        $BlocStructureModels->sort_id = 1;
        $BlocStructureModels->create_time = time();

        if( ! $BlocStructureModels->save()){
            throw new \Exception("保存bloc_structure表失败");
        }

        return $BlocStructureModels;
    }
}
