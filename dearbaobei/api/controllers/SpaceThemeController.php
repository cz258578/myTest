<?php

namespace api\controllers;

use Yii;
use api\controllers\AppController;
use api\models\User;
use api\models\Student;
use api\models\SpaceTheme;
use api\models\UserStudent;
use api\models\BClass;
use api\models\SpaceThemeImage;
use api\models\SpaceThemeVideo;
use api\models\SpaceThemePraise;
use api\models\SpaceThemeComments;
use common\uitl\ErrorHelper;

/**   
*
* 班级圈
* 
* @author weinengyu   
* 
*/
class SpaceThemeController extends AppController
{
    /**   
    * 班级圈列表
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionIndex()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $issuerType = trim($this->getParam('issuerType'));  // 发布人类型, -1代表全部（默认）, 1我家发布, 2老师发布, 3 其他家发布
        $studentId = trim($this->getParam('studentId')); // 学生Id

        /* 处理分页信息 */
        $pageNum = (int)$this->getParam('currentPage', false, 1); // 当前请求页码        
        $pageNum = 1; // 当前请求页码        
        $pageAmount = 20; // 每页显示多少条
        $pageNum = $pageNum < 1 ? 1 : $pageNum;
        $offset = ($pageNum-1) * $pageAmount;

        $slefFamilyStduentIds = []; // 自己家所有的学生Id

        /* 查询该用户绑定的所有学生班级 */
        $userStudents = UserStudent::find()->where('user_id=:user_id')->params([':user_id' => $userId])->groupBy('student_id')->all();
        if (count($userStudents) < 1) {
            /* 账户未绑定任何学生 */
            $this->Error(ErrorHelper::USER_BINDED_STUDENT_IS_EMPTY);
        }

        $classIds = [];
        foreach ($userStudents as $userStudent) {
            $tempStudentModel = Student::findOne($userStudent->student_id);
            $tempClassModel = BClass::findOne($tempStudentModel? $tempStudentModel->class_id: 0);
            if ($tempClassModel) {

                $slefFamilyStduentIds[] = $userStudent->student_id;
                $classIds[] = $tempClassModel->id;
            }
        }

        $classIds = array_unique($classIds);
        
        /* 校验数据的合法性 */
        if ($studentId != -1) {
            $tempStudentModel = Student::findOne($studentId);
            $tempClassModel = BClass::findOne($tempStudentModel? $tempStudentModel->class_id: 0);
            if ( ! in_array($tempClassModel->id, $classIds)) {
                $this->Error(ErrorHelper::USER_NOT_BINDED_STUDENT);
            }

            /* 把其他的班级清空, 只留要查询的 */
            $classIds = [];
            array_push($classIds, $tempClassModel->id);
        }

        $classIdsStr = implode(',', $classIds);

        /* 优化查询 */
        $searchWhere = [];
        $havingWhere = ''; // having 条件
        if (count($classIds) == 1) {
            $searchWhere[] = "st.class_id={$classIds[0]}";
        } else {
            $searchWhere[] = "st.class_id IN ({$classIdsStr})";
        }

        $result = ['hasMore' => false, 'nextPageNum' => 0, 'commentList' => []];

        /* 读取配置文件里面所有的关系类型 */
        $relationshipTypes = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型

        /* 查询当前用户关联的所有学生 */
        $spaceThemeQuery = new yii\db\Query();
        $spaceThemeQuery->select([
                           'st.id AS id',
                           'st.user_id AS user_id',
                           'st.student_id AS student_id',
                           'st.type AS themeType',
                           'st.content AS content',
                           'st.create_time AS createTime',
                           'st.is_allow_reply AS isAllowReply',
                        ])
                    ->from(SpaceTheme::tableName() . ' AS st');

        $spaceThemeQuery->leftJoin(User::tableName() . ' AS u', 'st.user_id=u.id');
        $slefFamilyStduentIdsStr = implode(',', $slefFamilyStduentIds);
        /* 根据条件查询数据 */
        if ($issuerType != -1) {
            if ($issuerType == 1 || $issuerType == 3) {
                $searchWhere[] = 'u.teacher_id=0';

                /* 查询自己家所有成员 */
                if ($issuerType == 1) { // 只查询自己家的
                    $havingWhere = "student_id IN ({$slefFamilyStduentIdsStr})";

                } else { // 查询除了自己家的, 就是其他家的
                    $searchWhere[] = "st.public_type=1"; // 只能查看其他家公开的主题
                    $havingWhere = "student_id NOT IN ({$slefFamilyStduentIdsStr})";
                }

                $spaceThemeQuery->having($havingWhere);
            } else {
                $searchWhere[] = 'u.teacher_id>0';
            }
        } else {
            /* 如果主题的公开类型为私密, 可以是老师发布的, 也可以是自己家庭成员发布的 */
            $havingWhere = "(u.teacher_id>0 OR student_id IN ({$slefFamilyStduentIdsStr}) OR st.public_type=1)";
        }

        $searchWhereStr = implode(' AND ', $searchWhere);

        $spaceThemes = $spaceThemeQuery->where($searchWhereStr)->orderBy('st.id DESC')->all();

        /* 处理空间主题信息 */
        foreach ($spaceThemes as $spaceTheme) {

            $tempThemeListData = [];
            $tempThemeListData['themeId'] = (int)$spaceTheme['id']; // 主题Id
            $tempThemeListData['userId'] = (int)$spaceTheme['user_id']; // 发布用户Id
            $tempThemeListData['isAuthor'] = (int)$spaceTheme['user_id'] == $userId? true: false; // 是否是自己发布的
            $tempThemeListData['content'] = $spaceTheme['content']; // 主题内容
            $tempThemeListData['isAllowReply'] = $spaceTheme['isAllowReply']; // 是否允许回复
            $tempThemeListData['themeType'] = $spaceTheme['themeType']; // 主题的类型, 1图片, 2视频
            $tempThemeListData['createTime'] = $spaceTheme['createTime']; // 发布时间
           
            /* 所属账号信息 */
            $tempUserInfo = UserStudent::getUserInfo($tempThemeListData['userId'], $spaceTheme['student_id'], $relationshipTypes); 
            $tempThemeListData['userName'] = isset($tempUserInfo['userName'])? $tempUserInfo['userName']: ''; // 发布用户称呼
            $tempThemeListData['userType'] = isset($tempUserInfo['userType'])? (int)$tempUserInfo['userType']: 1; // 发布用户类型， 1家长， 2老师
            $tempThemeListData['uMengUserId'] = $tempThemeListData['userType'] == 1? $spaceTheme['user_id'] . '@' . $spaceTheme['student_id']: $spaceTheme['user_id']; // 友盟的用户Id

            /* 主题图片列表 */
            $tempThemeListData['imageList'] = [];
            if ($tempThemeListData['themeType'] == 1) {
                $spaceThemeImages = SpaceThemeImage::find()->where('theme_id=:theme_id')->params([':theme_id' => $tempThemeListData['themeId']])->all();
                foreach ($spaceThemeImages as $spaceThemeImage) {
                    $tempThemeListData['imageList'][] = ['samllImgUrl' => $spaceThemeImage->thumb_path, 'imgUrl' => $spaceThemeImage->image_path];
                }
            }

            /* 主题视频 */
            $tempThemeListData['video'] = [];
            if ($tempThemeListData['themeType'] == 2) {
                $spaceThemeVideo = SpaceThemeVideo::find()->where('theme_id=:theme_id')->params([':theme_id' => $tempThemeListData['themeId']])->one();
                if ($spaceThemeVideo) {
                    $tempThemeListData['video'][] = ['playUrl' => $spaceThemeVideo->video_path, 'coverImageUrl' => $spaceThemeVideo->cover_image];
                }
            }

            /* 主题点赞列表 */
            $tempThemeListData['praiseUserList'] = [];
            $themePraises = SpaceThemePraise::find()->where('theme_id=:theme_id')->params([':theme_id' => $tempThemeListData['themeId']])->all();
            foreach ($themePraises as $themePraise) {
                 /* 所属账号信息 */
                $tempUserInfo = UserStudent::getUserInfo($tempThemeListData['userId'], $spaceTheme['student_id'], $relationshipTypes);

                $tempThemePraiseData = [];
                $tempThemePraiseData['userName'] = isset($tempUserInfo['userName'])? $tempUserInfo['userName']: ''; // 发布用户称呼
                $tempThemePraiseData['userType'] = isset($tempUserInfo['userType'])? (int)$tempUserInfo['userType']: 1; // 发布用户类型， 1家长， 2老师

                $tempThemeListData['praiseUserList'][] = $tempThemePraiseData;
            }
            $tempThemeListData['praiseNum'] = count($tempThemeListData['praiseUserList']); // 点赞数量

            /* 主题评论列表 */
            $tempThemeListData['commentList'] = [];

            $themeComments = SpaceThemeComments::find()->where('theme_id=:theme_id')->params([':theme_id' => $tempThemeListData['themeId']])->all();
            foreach ($themeComments as $themeComment) {
                $tempThemeCommentData = [];

                $tempThemeCommentData['commentId'] = $themeComment->id; // 评论Id
                $tempThemeCommentData['isAuthor'] = $themeComment->user_id == $userId? true: false; // 是否是自己评论的
                $tempThemeCommentData['content'] = $themeComment->content; // 评论内容
                $tempThemeCommentData['createTime'] = $themeComment->create_time; // 评论时间

                /* 评论者所属账号信息 */
                $fromUserInfo = UserStudent::getUserInfo($themeComment->user_id, $themeComment->student_id, $relationshipTypes);
                $tempFromUserType = isset($fromUserInfo['userType'])? (int)$fromUserInfo['userType']: 1;

                $tempThemeCommentData['fromUmengUserId'] = $tempFromUserType == 1? $themeComment->user_id . '@' . $themeComment->student_id: $themeComment->user_id; // 评论者用户友盟Id
                $tempThemeCommentData['fromUserType'] = $tempFromUserType; // 评论用户类型, 1家长, 2老师
                $tempThemeCommentData['fromUserName'] = isset($fromUserInfo['userName'])? $fromUserInfo['userName']: ''; // 评论者名称

                /* 评论者所属账号信息 */
                $tempThemeCommentData['toUserId'] = 0; // 被回复给谁的用户Id, 如果回复主题则为空
                $tempThemeCommentData['toUserType'] = 0; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
                $tempThemeCommentData['toUserName'] = ''; // 被回复给谁的用户名称, 如果回复主题则为空

                /* 如果回复的是评论则显示回复谁 */
                if ($themeComment->replyto_comment_id > 0) {
                    $SpaceThemeCommentModel = SpaceThemeComments::findOne($themeComment->replyto_comment_id);
                    
                    if ($SpaceThemeCommentModel) {
                        $toUserInfo = UserStudent::getUserInfo($SpaceThemeCommentModel->user_id, $SpaceThemeCommentModel->student_id, $relationshipTypes);
                        $tempToUserType = isset($toUserInfo['userType'])? (int)$toUserInfo['userType']: 1;

                        $tempThemeCommentData['toUserId'] = $tempToUserType == 1? $SpaceThemeCommentModel->user_id . '@' . $SpaceThemeCommentModel->student_id: $SpaceThemeCommentModel->user_id; // 被回复给谁的用户Id, 如果回复主题则为空
                        $tempThemeCommentData['toUserType'] = $tempToUserType; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
                        $tempThemeCommentData['toUserName'] = isset($toUserInfo['userName'])? $toUserInfo['userName']: '';; // 被回复给谁的用户名称, 如果回复主题则为空
                    }
                }

                $tempThemeListData['commentList'][] = $tempThemeCommentData;
            }

            $result['commentList'][] = $tempThemeListData;
        }

        /* 计算是否还有下一页, 下一页的页码 */
        if (count($result['commentList']) > $pageAmount) {
            $re['hasMore'] = true;
            $re['nextPageNum'] = $pageNum + 1;
            array_pop($result['commentList']);
        }

        $this->Success($result);
    }

    /**   
    * 添加空间主题
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionAdd()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $studentId = (int)$this->getParam('studentId'); // 学生Id
        $publicType = (int)$this->getParam('publicType'); // 公开类型(1公开, 2私密)
        $type = (int)$this->getParam('type'); // 主题的类型, 1图片, 2视频
        $isAllowReply = (bool)trim($this->getParam('isAllowReply')); // true|false是否允许回复
        $content = trim($this->getParam('content')); // 主题的内容

        $images = json_decode($this->getParam('images')) && is_array(json_decode($this->getParam('images')))? json_decode($this->getParam('images')): []; // 解析图片数据
        $videos = json_decode($this->getParam('videos')) && is_array(json_decode($this->getParam('videos')))? json_decode($this->getParam('videos')): []; // 解析视频数据

        /* 查询该用户是否绑定了该学生 */
        $userStudent = UserStudent::find()->where('user_id=:user_id AND student_id=:student_id')->params([':user_id' => $userId, ':student_id' => $studentId])->one();
        if (! $userStudent) {
            $this->Error(ErrorHelper::USER_NOT_BINDED_STUDENT);
        }

        /* 查询学生信息 */
        $studentModel = Student::findOne($studentId);      
        if (! $studentModel) {
            $this->Error(ErrorHelper::STUDENT_NO_EXISTS);            
        }

        $spaceThemeModel = new SpaceTheme();
        $spaceThemeModel->class_id = $studentModel->class_id; // 班级Id
        $spaceThemeModel->student_id = $studentModel->id; // 学生Id
        $spaceThemeModel->user_id = $userId; // 用户Id
        $spaceThemeModel->is_allow_reply = $isAllowReply; // 是否允许回复
        $spaceThemeModel->public_type = $isAllowReply; // 是否允许回复
        $spaceThemeModel->type = $type == 1? 1: 2; // 主题的类型
        $spaceThemeModel->content = $content; // 主题的内容
        $spaceThemeModel->praise_num = 0; // 点赞的数量
        $spaceThemeModel->create_time = time(); // 发布时间

         /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {
            if ( ! $spaceThemeModel->save()) {
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            /* 如果主题的类型是图片, 否是就是视频 */
            if ($type == 1) {
                foreach ($images as $image) {
                    $spaceThemeImageModel = new SpaceThemeImage();
                    $spaceThemeImageModel->theme_id = 2; // 主题Id
                    $spaceThemeImageModel->thumb_path = isset($image['samllImgUrl'])? $image['samllImgUrl']: ''; // 缩略图路径
                    $spaceThemeImageModel->image_path = isset($image['imgUrl'])? $image['imgUrl']: ''; // 缩略图路径
                    $spaceThemeImageModel->save();
                }
            } else {
                foreach ($images as $image) {                
                    $spaceThemeVideoModel = new SpaceThemeVideo();
                    $spaceThemeVideoModel->theme_id = 2; // 主题Id
                    $spaceThemeVideoModel->video_path = isset($image['playUrl'])? $image['playUrl']: ''; // 视频路径
                    $spaceThemeVideoModel->cover_image = isset($image['coverImageUrl'])? $image['coverImageUrl']: ''; // 封面图
                    $spaceThemeVideoModel->duration = ''; // 时长
                    $spaceThemeVideoModel->save();            
                }
            }

            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        /* 处理返回值 */
        $result = [];
        $result['themeId'] = $spaceThemeModel->id; // 新增空间主题Id
        
        /* 读取配置文件里面所有的关系类型 */
        $relationshipTypes = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型

        /* 账号信息 */
        $toUserInfo = UserStudent::getUserInfo($userId, $studentId, $relationshipTypes);
        $tempToUserType = isset($toUserInfo['userType'])? (int)$toUserInfo['userType']: 1;

        $result['uMengUserId'] = $tempToUserType == 1? $userId . '@' . $studentModel->id: $userId; // 被回复给谁的用户Id, 如果回复主题则为空
        $result['userType'] = $tempToUserType; // 被回复用户类型, 1家长, 2老师,如果回复主题则为空
        $result['userName'] = isset($toUserInfo['userName'])? $toUserInfo['userName']: '';; // 被回复给谁的用户名称, 如果回复主题则为空
        $result['createTime'] = $spaceThemeModel->create_time; // 发布时间

        $this->Success($result);
    }
 
    /**   
    * 点赞主题
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionAddPraise()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $themeId = (int)$this->getParam('themeId'); // 主题Id
        $studentId = (int)$this->getParam('studentId'); // 学生Id
        
        /* 查询主题信息 */
        $spaceThemeModel = SpaceTheme::findOne($themeId);
        if ( ! $spaceThemeModel) {
            $this->Error(ErrorHelper::GLOBAL_INVALID_PARAM);
        }
        
        /* 查询该用户是否绑定了该学生 */
        $userStudent = UserStudent::find()->where('user_id=:user_id AND student_id=:student_id')->params([':user_id' => $userId, ':student_id' => $studentId])->one();
        if (! $userStudent) {
            $this->Error(ErrorHelper::USER_NOT_BINDED_STUDENT);
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

        /* 查询用户是否点赞过 */
        $userIsPraised = SpaceThemePraise::find()->where('theme_id=:theme_id')->params([':theme_id' => $spaceThemeModel->id])->one();
        if ($userIsPraised) {
            $this->Error(ErrorHelper::SPACE_THEME_IS_PRAISED);
        }

        $spaceThemePraiseModel = new SpaceThemePraise();
        $spaceThemePraiseModel->theme_id = $spaceThemeModel->id; // 主题Id
        $spaceThemePraiseModel->user_id = $userId; // 用户Id
        $spaceThemePraiseModel->student_id = $studentId; // 学生Id
        $spaceThemePraiseModel->create_time = time(); // 点赞时间

        /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {
            if ( ! $spaceThemePraiseModel->save()) {
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            $spaceThemeModel->praise_num += 1; // 空间主题点赞数量加1
            if ($spaceThemeModel->save()) {
                $innerTransaction->rollBack();
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        $this->Success([]);
    }

    /**   
    * 删除空间主题
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionDelete()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $themeId = (int)$this->getParam('themeId'); // 主题Id

        /* 查询主题信息 */
        $spaceThemeModel = SpaceTheme::findOne($themeId);
        if ( ! $spaceThemeModel || $spaceThemeModel->user_id != $userId) {
            $this->Error(ErrorHelper::GLOBAL_INVALID_PARAM);
        }

        /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {
            if ( ! $spaceThemeModel->delete()) {
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            /* 删除评论 */
            SpaceThemeComments::deleteAll('theme_id=:theme_id', ['theme_id' => $themeId]);
            /* 删除图片 */
            SpaceThemeImage::deleteAll('theme_id=:theme_id', ['theme_id' => $themeId]);
            /* 删除视频 */
            SpaceThemeVideo::deleteAll('theme_id=:theme_id', ['theme_id' => $themeId]);
            /* 删除点赞 */
            SpaceThemePraise::deleteAll('theme_id=:theme_id', ['theme_id' => $themeId]);

            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        $this->Success([]);
    }
}