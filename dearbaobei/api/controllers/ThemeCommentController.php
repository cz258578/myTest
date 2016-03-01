<?php

namespace api\controllers;

use Yii;
use api\controllers\AppController;
use api\models\SpaceThemeComments;
use api\models\Student;
use api\models\UserStudent;
use api\models\SpaceTheme;
use common\uitl\ErrorHelper;
use common\openIM\openIMHelper;
use common\uMeng\sendNoticeHelper;

/**   
*
* 班级圈评论
* 
* @author weinengyu   
* 
*/
class ThemeCommentController extends AppController
{
    /**   
    * 回复评论
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionAdd()
    {
        $demo = new sendNoticeHelper("5695bb8767e58ec8280007f5", "bbmbgwxr6wv8r6toumd7wznrhwhdsl6o");
        $demo->sendAndroidUnicast();

        var_dump($demo);exit;

        $userId = $this->checkUserToken(); // 用户Id
        $themeId = (int)$this->getParam('themeId'); // 主题Id
        $commentId = (int)$this->getParam('commentId'); // 回复Id
        $studentId = (int)$this->getParam('studentId'); // 学生Id
        $content = trim($this->getParam('content')); // 回复内容

        /* 查询该用户是否绑定了该学生 */
        $userStudent = UserStudent::find()->where('user_id=:user_id AND student_id=:student_id')->params([':user_id' => $userId, ':student_id' => $studentId])->one();
        if (! $userStudent) {
            $this->Error(ErrorHelper::USER_NOT_BINDED_STUDENT);
        }

        /* 查询主题信息 */
        $spaceThemeModel = SpaceTheme::findOne($themeId);
        if ( ! $spaceThemeModel) {
            $this->Error(ErrorHelper::GLOBAL_INVALID_PARAM);
        }

        /* 主题是否允许回复 */
        if ($spaceThemeModel->is_allow_reply != 1) {
            $this->Error(ErrorHelper::SPACE_THEME_NO_ALLOW_REPLY);
        }

        /* 查询学生信息 */
        $studentModel = Student::findOne($studentId);      
        if (! $studentModel) {
            $this->Error(ErrorHelper::STUDENT_NO_EXISTS);            
        }

        /* 查询该学生是否有权限回复该条主题 */
        if ($spaceThemeModel->class_id != $studentModel->class_id) {
            $this->Error(ErrorHelper::SPACE_THEME_NO_PERMISSIONS_COMMENT);
        }

        $spaceThemeCommentModel = new SpaceThemeComments();
        $spaceThemeCommentModel->theme_id = $themeId; // 主题Id
        $spaceThemeCommentModel->user_id = $userId; // 用户Id
        $spaceThemeCommentModel->student_id = $studentId; // 学生Id
        $spaceThemeCommentModel->replyto_comment_id = $commentId; // 回复评论Id
        $spaceThemeCommentModel->content = $content; // 回复内容
        $spaceThemeCommentModel->create_time = time(); // 回复时间
        
        if ( ! $spaceThemeCommentModel->save()) {
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        /* 处理返回值 */
        $result = [];
        $result['commentId'] = $spaceThemeCommentModel->id; // 评论Id
        
        /* 读取配置文件里面所有的关系类型 */
        $relationshipTypes = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型

        /* 账号信息 */
        $formUserInfo = UserStudent::getUserInfo($userId, $studentModel->id, $relationshipTypes);
        $tempFromUserType = isset($formUserInfo['userType'])? (int)$formUserInfo['userType']: 1;

        $result['fromUMengUserId'] = $tempFromUserType == 1? $userId . '@' . $studentModel->id: $userId; // 评论者友盟用户Id
        $result['fromUserType'] = $tempFromUserType; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
        $result['fromUserName'] = isset($formUserInfo['userName'])? $formUserInfo['userName']: '';; // 被回复给谁的用户名称, 如果回复主题则为空

        $result['toUmengUserId'] = 0;
        $result['toUserType'] = 0; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
        $result['toUserName'] = ''; // // 被回复给谁的用户名称, 如果回复主题则为空

        $replytoCommentModel = SpaceThemeComments::findOne($spaceThemeCommentModel->replyto_comment_id);
        if ($replytoCommentModel) {
            /* 账号信息 */
            $toUserInfo = UserStudent::getUserInfo($replytoCommentModel->user_id, $replytoCommentModel->student_id, $relationshipTypes);
            $tempToUserType = isset($toUserInfo['userType'])? (int)$toUserInfo['userType']: 1;

            $result['toUmengUserId'] = $tempToUserType == 1? $replytoCommentModel->user_id . '@' . $replytoCommentModel->student_id: $replytoCommentModel->user_id; // 评论者友盟用户Id
            $result['toUserType'] = $tempToUserType; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
            $result['toUserName'] = isset($toUserInfo['userName'])? $toUserInfo['userName']: '';; // 被回复给谁的用户名称, 如果回复主题则为空
        }

        $result['createTime'] = $spaceThemeCommentModel->create_time; // 评论时间
        $this->Success($result);
    }

    /**   
    * 删除空间主题评论
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionDelete()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $commentId = (int)$this->getParam('commentId'); // 评论Id

        /* 查询评论信息 */
        $spaceThemeCommentModel = SpaceThemeComments::findOne($commentId);
        if ( ! $spaceThemeCommentModel || $spaceThemeCommentModel->user_id != $userId) {
            $this->Error(ErrorHelper::GLOBAL_INVALID_PARAM);
        }

        /* 删除评论 */
        if ( ! $spaceThemeCommentModel->delete()) {
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        $this->Success([]);
    }
}