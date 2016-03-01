<?php

namespace backend\controllers;

use backend\models\AdminRole;
use Yii;

/**   
* 管理后台默认控制器
*   
* 管理后台默认控制器
* 
* @author weinengyu   
* 
*/
class DefaultController extends AuthBaseController
{

    public $userInfo;
    public $userRoleInfo;
    /**   
    * 功能描述
    *  
    * @param 类型 $fields 描述  
    *  
    * @author 
    *
    * @return 类型 描述
    */
    public function actionIndex() {
        $this->layout = 'main';

        $this->userInfo = yii::$app->user->identity;
        $this->userRoleInfo = AdminRole::findOne($this->userInfo->role_id);

        return $this->render('index');
    }
}