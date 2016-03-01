<?php
namespace backend\controllers;


use Yii;
use yii\web\Controller;
use backend\models\AdminUserLogin;
/**   
* 管理后台默认控制器
*   
* 管理后台默认控制器
* 
* @property string layout 布局
*
* @author   luopengfei 
* 
*/
class SiteController extends BaseController
{
    public $layout = 'base';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    /**   
    * 管理员登录
    *  
    * @param int $id 用户ID  
    *  
    * @author luopengfei
    *
    * @return 登录
    */
    public function actionLogin()
    {
        // 123456  hackjiyi
        //var_dump(Yii::$app->user->isGuest);die;
        //验证登陆
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['default/index']);
        }

        $model = new AdminUserLogin();
        if(Yii::$app->request->post()){
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $error['status'] = 1;
                $error['info'] = yii::$app->urlManager->createUrl(['default/index']);
                $this->Json($error);
            }else{
                $error = $model->getErrors();
                foreach($error as $v){
                    $error['status'] = 0;
                    $error['info'] = $v;
                    $this->Json($error);
                }
            }
        }

        return $this->render('login',['model' => $model]);
    }

    /**   
    * 登出
    *  
    * @param 类型 $fields 描述  
    *  
    * @author 
    *
    * @return 类型 描述
    */
    public function actionLogout()
    {
       @session_start();
       unset($_SESSION['__Manage']);
       return $this->redirect(['default/index']);
    }  
}
