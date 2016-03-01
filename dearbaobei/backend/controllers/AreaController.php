<?php
namespace backend\controllers;

use Yii;
use common\models\Area;

/**   
* 区县管理  
*   
* 控制器用来管理区县相关的数据管理 
*
* @author   weinengyu 
* 
*/
class AreaController extends BaseController
{

    /**   
    * 根据城市ID获取所有城市区县JSON
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionGetAllJson($city_id)
    {
        $datas = Area::find()->where('father_id=:father_id')->params([':father_id' => $city_id])->all();
        $resultJson = [];

        foreach ($datas as $dataKey => $data) {
            $tempJson = [];

            $tempJson['area_id'] = $data->area_id;
            $tempJson['name'] = $data->area;

            $resultJson[] = $tempJson;
        }
        $this->Json($resultJson);
    }
}