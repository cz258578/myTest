<?php
namespace backend\controllers;

use Yii;
use common\models\Province;

/**   
* 省份管理  
*   
* 控制器用来管理省份相关的数据管理 
*
* @author   weinengyu 
* 
*/
class ProvinceController extends BaseController
{

    /**   
    * 获取所有省份JSON
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionGetAllJson()
    {

        $datas = Province::find()->all();
        $resultJson = [];

        foreach ($datas as $dataKey => $data) {
            $tempJson = [];

            $tempJson['province_id'] = $data->province_id;
            $tempJson['name'] = $data->province;

            $resultJson[] = $tempJson;
        }
        $this->Json($resultJson);
    }
}