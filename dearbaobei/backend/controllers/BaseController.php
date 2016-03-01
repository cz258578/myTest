<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**   
* 管理后台默认控制器
*   
* 管理后台默认控制器
* 
* @author weinengyu   
* 
*/
class BaseController extends Controller
{
    public $layout = 'base';

    static public $upImageUrl = '/uploads/photo/';
    /**
     * 不需权限认证的controller
     */
    public $excludeCtrls = ['Base', 'BaseBootstrap', 'Site', 'Default', 'Upload'];
    /**
     * 不需权限认证的action
     */
    public $excludeActions = ['ajax', 'myaccount', 'showerror', 'showsuccess'];
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            //'access' => [
            //    'class' => AccessControl::className(),
            //    'rules' => ['@']
            //],
            //'verbs' => [
            //    'class' => VerbFilter::className(),
            //    'actions' => [
            //        'delete' => ['post'],
            //    ],
            //],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function init()
    {
        parent::init();
        Yii::$app->charset = 'utf-8';
        header('Content-Type:text/html; charset=utf-8');
    }
    
    public function beforeAction($action)
    {
        
        return true;
    }

    /**
     * JSON输出并结束
     * @param array $_arr
     */
    public function Json($_arr)
    {
        header('Content-Type:application/json; charset=utf-8;');
        echo(json_encode($_arr));
        Yii::$app->end();
    }
}
