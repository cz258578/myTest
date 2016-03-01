<?php
namespace backend\controllers;

use Yii;
use common\models\City;

/**   
* 城市管理  
*   
* 控制器用来管理城市相关的数据管理 
*
* @author   weinengyu 
* 
*/
class CityController extends BaseController
{

    /**   
    * 根据省份ID获取所有城市JSON
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionGetAllJson($province_id)
    {

        $datas = City::find()->where('father_id=:father_id')->params([':father_id' => $province_id])->all();
        $resultJson = [];

        foreach ($datas as $dataKey => $data) {
            $tempJson = [];

            $tempJson['city_id'] = $data->city_id;
            $tempJson['name'] = $data->city;

            $resultJson[] = $tempJson;
        }
        $this->Json($resultJson);
    }
}