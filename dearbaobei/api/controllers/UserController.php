<?php

namespace api\controllers;

use Yii;
use api\controllers\AppController;
use api\models\User;
use api\models\Student;
use api\models\UserStudent;
use api\models\BClass;
use api\models\School;
use api\models\ClassCurricula;
use api\models\TeaCurriSubject;
use api\models\Teacher;
use api\models\Subject;
use common\uitl\ErrorHelper;
use common\openIM\openIMHelper;

/**   
*
* 用户控制器
* 
* @author weinengyu   
* 
*/
class UserController extends AppController
{
    /**   
    * 注册用户
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionReg()
    {
        $userPhone = trim($this->getParam('userPhone')); // 用户手机
        $userPw = trim($this->getParam('userPw')); // 用户密码

        $result = [];

        /* 验证手机号码 */
        if ( ! \common\uitl\UtilHelper::CheckMobile($userPhone)) {
            $this->Error(ErrorHelper::USER_PHONE_REQUIRED);
        }

        /* 查询用户是否存在 */
        $userExist = User::find()->where('phone=:phone AND teacher_id=0 AND is_family=1')->params([':phone' => $userPhone])->one();
        if ($userExist) {
            $this->Error(ErrorHelper::USER_PHONE_EXISTS);
        }

        /* 检查用户密码长度 */
        if ( ! preg_match("/^[\w\W]{6,15}$/i", $userPw)) {
            $this->Error(ErrorHelper::USER_PASSWORD_REQUIRED);
        }

        /* 创建用户 */
        $userModel = new User();
        $userModel->name = ''; // 账户姓名
        $userModel->username = $userPhone; // 账户名
        $userModel->password = User::encryptPwd($userPw); // 密码
        $userModel->teacher_id = 0; // 绑定的老师
        $userModel->is_family = 1; // 是否是家长
        $userModel->phone = $userPhone; // 手机号码
        $userModel->email = ''; // 邮箱
        $userModel->qq = ''; // QQ
        $userModel->weixin = ''; // 微信
        $userModel->auth_key = ''; // 秘钥
        $userModel->status = 1; // 状态
        $userModel->create_time = time(); // 创建时间

        if ( ! $userModel->save()) {
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        $this->Success($result);
    }

    /**   
    * 用户登录
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionLogin()
    {
        $userPhone = trim($this->getParam('userPhone')); // 用户手机
        $userPw = trim($this->getParam('userPw')); // 用户密码

        $result = [];

        /* 查询用户是否存在 */
        $userModel = User::find()->where('phone=:phone AND teacher_id=0 AND is_family=1 AND status=1')->params([':phone' => $userPhone])->one();
        if ( ! $userModel) {
            $this->Error(ErrorHelper::USER_NOT_FOUND);
        }

        /* 校验密码 */
        $passwd = User::encryptPwd($userPw);
        if ($passwd != $userModel->password) {
            $this->Error(ErrorHelper::USER_INVALID_PASSWD);
        }

        /* 处理返回值 */
        $result['userId'] = (int)$userModel->id;
        $result['token'] = $this->getEncodeToken($result['userId']); // 秘钥
        $result['icon'] = $userModel->small_avator; // 用户头像url   可以为空

        $result['uMeng'] = []; // 友盟的信息

        $result['studentChilds'] = []; // 绑定学生信息
        
        /* 查询当前用户关联的所有学生 */
        $userStudentQuery = new yii\db\Query();
        $userStudents = $userStudentQuery->select([
                            'us.relationship AS relationship',
                            's.id AS studentId',
                            's.name AS studetName',
                            's.sex AS sex',
                            's.phone AS studetPhone',
                            'bc.id AS classId',
                            'bc.name AS className',
                            'sch.id AS schoolId',
                            'sch.name AS schoolName'
                        ])
                    ->from(UserStudent::tableName() . ' AS us')
                    ->leftJoin(Student::tableName() . ' AS s', 'us.student_id=s.id')
                    ->leftJoin(BClass::tableName() . ' AS bc', 's.class_id=bc.id')
                    ->leftJoin(School::tableName() . ' AS sch', 's.school_id=sch.id')
                    ->where('us.user_id=:user_id')
                    ->params([':user_id' => $userModel->id])
                    ->all();

            $tempUserStudent['relationshipName'] = isset($userStudent['relationship']); // 学校名称

        $relationshipType = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型
             
        /* 处理数据 */
        foreach ($userStudents as $userStudent) {
            $tempUserStudent = [];
            $tempUserStudent['studentId'] = (int)$userStudent['studentId']; // 学生ID
            $tempUserStudent['studetName'] = $userStudent['studetName']; // 学生姓名
            $tempUserStudent['studetSex'] = $userStudent['sex'] == 1? '男': '女'; // 学生性别
            $tempUserStudent['studetPhone'] = (int)$userStudent['studetPhone']; // 学生手机号码
            $tempUserStudent['classId'] = (int)$userStudent['classId']; // 班级Id
            $tempUserStudent['className'] = $userStudent['className']; // 班级名称
            $tempUserStudent['schoolId'] = $userStudent['schoolId']; // 学校Id
            $tempUserStudent['schoolName'] = $userStudent['schoolName']; // 学校名称
            $tempUserStudent['schoolName'] = $userStudent['schoolName']; // 学校名称
            $tempUserStudent['relationshipName'] = isset($relationshipType[$userStudent['relationship']])? $relationshipType[$userStudent['relationship']]: ''; // 学校名称

            /* 获取友盟信息 */
            $tempUMeng = [];
            $tempUMeng['userId'] = $result['userId'] . '@' . $tempUserStudent['studentId'];
            $tempUMeng['userPw'] = md5($userModel->password); // md5 再加密一次
            $result['uMeng'][] = $tempUMeng;

            $result['studentChilds'][] = $tempUserStudent;
        }

        $result['studentNum'] = count($userStudents); // 绑定学生个数

        $this->Success($result);
    }

    /**   
    * 修改用户密码
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionResetPassword()
    {
        $userPhone = trim($this->getParam('userPhone')); // 用户手机
        $userNewPw = trim($this->getParam('userNewPw')); // 用户新密码
        $isFamily = (int)$this->getParam('isFamily'); // 是否是家长账号
        $smsCode = trim($this->getParam('smsCode')); // 短信验证

        /* 检查用户密码长度 */
        if ( ! preg_match("/^[\w\W]{6,15}$/i", $userNewPw)) {
            $this->Error(ErrorHelper::USER_PASSWORD_REQUIRED);
        }

        $smsCheckResult = \common\uitl\UtilHelper::CheckSmsCode($userPhone, $smsCode);
        
        /* 如果校验短信验证码失败或者错误, 返回错误信息给APP */
        if ( ! $smsCheckResult || $smsCheckResult['success'] == false) {
            $constantCode = $smsCheckResult['constantCode'];

            $this->Error(eval("return \common\uitl\ErrorHelper::{$constantCode};"));
        }

        /* 查询用户是否存在 */
        if ($isFamily) { // 如果是家长账号
            $userModel = User::find()->where('phone=:phone AND teacher_id=0 AND is_family=1 AND status=1')->params([':phone' => $userPhone])->one();
        } else { // 否则就是老师账号
            $userModel = User::find()->where('phone=:phone AND teacher_id>0 AND is_family=0 AND status=1')->params([':phone' => $userPhone])->one();
        }

        if ( ! $userModel) {
            $this->Error(ErrorHelper::USER_NOT_FOUND);
        }

        $userModel->password = User::encryptPwd($userNewPw);

        /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {

            /* 保存新密码 */
            if ( ! $userModel->save()) {
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            /* 调用友盟账号修改密码 */
            $userinfos = [];
            $userinfos['userid'] = 'imuser1234';
            $userinfos['password'] = md5($userModel->password);

            $openIMHelper = new openIMHelper();

            $httpRequstObj = $openIMHelper->getHtttpRequst('OpenimUsersUpdateRequest');
            $httpRequstObj->setUserinfos(json_encode($userinfos));

            $resp = $openIMHelper->selfExecute($httpRequstObj);

            /* 如果更新成功 */
            if (isset($resp->uid_succ) && count($resp->uid_succ) > 0) {
                $innerTransaction->commit();
                $this->Success([]);
            } else {
                $innerTransaction->rollBack();
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }
    }

    /**   
    * 查找学校
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionSchoolList()
    {
        $this->checkUserToken(); // 校验通行证

        $schoolName = trim($this->getParam('schoolName')); // 学校名称

        $result = ['schoolList' => []];

        $schoolList = School::find()->where("name like '%{$schoolName}%'")->all();

        foreach($schoolList as $school) {
            $tempSchoolData = [];

            $tempSchoolData['schoolId'] = $school->id; //学校ID
            $tempSchoolData['schoolName'] = $school->name; //学校名称

            $tempSchoolData['cityName'] = \common\models\City::getNameById($school->city_id); //学校所在城市
            $tempSchoolData['areaName'] = \common\models\Area::getNameById($school->area_id); //学校所在城市区

            $result['schoolList'][] = $tempSchoolData;
        }

        $result['schoolNum'] = count($schoolList);
        $this->Success($result);
    }

    /**   
    * 查找学生
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionStudentList()
    {
        $this->checkUserToken(); // 校验通行证

        $schoolId = (int)$this->getParam('schoolId'); // 学校Id
        $studentName = trim($this->getParam('studentName')); // 学生名字

        $result = ['studentList' => []];

        $studentList = Student::find()->where("school_id=:school_id AND status=2 AND name like '%{$studentName}%'")->params([':school_id' => $schoolId])->all();

        foreach($studentList as $student) {
            $tempStudentData = [];

            $tempStudentData['studentId'] = $student->id; // 学生ID
            $tempStudentData['studentName'] = $student->name; // 学生名称

            /* 班级 */
            $classModel = Bclass::findOne($student->class_id);
            $tempStudentData['classId'] = $student->class_id; // 班级ID
            $tempStudentData['className'] = $classModel? $classModel->name: ''; // 班级名称

            /* 学校 */
            $SchoolModel = School::findOne($student->school_id);
            $tempStudentData['schoolId'] = $student->school_id; // 学校ID
            $tempStudentData['schoolName'] = $SchoolModel? $SchoolModel->name: ''; // 学校名称

            $tempStudentData['phone'] = $student->phone; // 手机号码

            $result['studentList'][] = $tempStudentData;
        }

        $result['studentNum'] = count($studentList);
        $this->Success($result);
    }

    /**   
    * 关联学生
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionBindStudent()
    {
        $userId = $this->checkUserToken(); // 用户Id
        $studentId = (int)$this->getParam('studentId'); // 学生Id
        $relationshipId = (int)$this->getParam('relationshipId'); // 关系Id
        $smsCode = trim($this->getParam('smsCode')); // 短信验证

        /* 查询用户是否存在 */
        $userModel = User::find()->where('id=:id AND status=1')->params([':id' => $userId])->one();
        if ( ! $userModel) {
            $this->Error(ErrorHelper::USER_NOT_FOUND);
        }

        /* 查询学生是否存在 */
        $studentModel = Student::find()->where('id=:id AND status=2')->params([':id' => $studentId])->one();
        if ( ! $studentModel) {
            $this->Error(ErrorHelper::STUDENT_NO_EXISTS);
        }

        $smsCheckResult = \common\uitl\UtilHelper::CheckSmsCode($studentModel->phone, $smsCode);
        
        /* 如果校验短信验证码失败或者错误, 返回错误信息给APP */
        if ( ! $smsCheckResult || $smsCheckResult['success'] == false) {
            $constantCode = $smsCheckResult['constantCode'];

            $this->Error(eval("return \common\uitl\ErrorHelper::{$constantCode};"));
        }

        /* 查询现在用户是否已绑定改学生 */
        $userStudentExist = UserStudent::find()->where('user_id=:user_id AND student_id=:student_id')->params([':user_id' => $userId, ':student_id' => $studentId])->one();     
        if ($userStudentExist) {
            $this->Error(ErrorHelper::USER_IS_BINDED_STUDENT);
        }

        /* 开启事务操作 */    
        $innerTransaction = yii::$app->db->beginTransaction();
        try {
            /* 新绑定学生 */
            $userStudentModel = new UserStudent();
            $userStudentModel->user_id = $userId;
            $userStudentModel->student_id = $studentId;
            $userStudentModel->relationship = $relationshipId;

            /* 如果保存失败,则返回错误信息 */
            if ( ! $userStudentModel->save()) {
                $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
            }

            /* 查询账号在友盟中是否存在 */
            $uMengUserid = $userId . '@' . $studentId;
            $openIMHelper = new openIMHelper();
            $httpRequstObj = $openIMHelper->getHtttpRequst('OpenimUsersGetRequest');
            $httpRequstObj->setUserids($uMengUserid);

            $resp = $openIMHelper->selfExecute($httpRequstObj);

            /* 如果账号在友盟里面不存在则添加进友盟 */
            if ( ! isset($resp->userinfos)) {
                /* 将该用户注册到友盟 */
                $userinfos = [];
                $userinfos['userid'] = $uMengUserid;
                $userinfos['password'] = md5($userModel->password);

                $openIMHelper1 = new openIMHelper();
                $httpRequstObj1 = $openIMHelper1->getHtttpRequst('OpenimUsersAddRequest');
                $httpRequstObj1->setUserinfos(json_encode($userinfos));
                $resp1 = $openIMHelper1->selfExecute($httpRequstObj1);

                if ( ! isset($resp1->uid_succ)) {

                    $innerTransaction->rollBack();

                    $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
                }
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {

            $innerTransaction->rollBack();
            $this->Error(ErrorHelper::GLOBAL_DB_FAILED);
        }

        /* 处理返回值 */
        $result = [];

        $result['uMeng'] = []; // 友盟的信息

        $result['studentChilds'] = []; // 绑定学生信息
        
        /* 查询当前用户关联的所有学生 */
        $userStudentQuery = new yii\db\Query();
        $userStudents = $userStudentQuery->select([
                            'us.relationship AS relationship',
                            's.id AS studentId',
                            's.name AS studetName',
                            's.sex AS sex',
                            's.phone AS studetPhone',
                            'bc.id AS classId',
                            'bc.name AS className',
                            'sch.id AS schoolId',
                            'sch.name AS schoolName'
                        ])
                    ->from(UserStudent::tableName() . ' AS us')
                    ->leftJoin(Student::tableName() . ' AS s', 'us.student_id=s.id')
                    ->leftJoin(BClass::tableName() . ' AS bc', 's.class_id=bc.id')
                    ->leftJoin(School::tableName() . ' AS sch', 's.school_id=sch.id')
                    ->where('us.user_id=:user_id')
                    ->params([':user_id' => $userModel->id])
                    ->all();

            $tempUserStudent['relationshipName'] = isset($userStudent['relationship']); // 学校名称

        $relationshipType = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型
             
        /* 处理数据 */
        foreach ($userStudents as $userStudent) {
            $tempUserStudent = [];
            $tempUserStudent['studentId'] = (int)$userStudent['studentId']; // 学生ID
            $tempUserStudent['studetName'] = $userStudent['studetName']; // 学生姓名
            $tempUserStudent['studetSex'] = $userStudent['sex'] == 1? '男': '女'; // 学生性别
            $tempUserStudent['studetPhone'] = (int)$userStudent['studetPhone']; // 学生手机号码
            $tempUserStudent['classId'] = (int)$userStudent['classId']; // 班级Id
            $tempUserStudent['className'] = $userStudent['className']; // 班级名称
            $tempUserStudent['schoolId'] = $userStudent['schoolId']; // 学校Id
            $tempUserStudent['schoolName'] = $userStudent['schoolName']; // 学校名称
            $tempUserStudent['schoolName'] = $userStudent['schoolName']; // 学校名称
            $tempUserStudent['relationshipName'] = isset($relationshipType[$userStudent['relationship']])? $relationshipType[$userStudent['relationship']]: ''; // 学校名称

            /* 获取友盟信息 */
            $tempUMeng = [];
            $tempUMeng['userId'] = $userId . '@' . $tempUserStudent['studentId'];
            $tempUMeng['userPw'] = md5($userModel->password); // md5 再加密一次
            $result['uMeng'][] = $tempUMeng;

            $result['studentChilds'][] = $tempUserStudent;
        }

        $result['studentNum'] = count($userStudents); // 绑定学生个数

        $this->Success($result);
    }

    /**   
    * 获取关系类型
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionRelationshipList()
    {        
        $this->checkUserToken();

        $result = ['relationshipList' => []];

        /* 读取配置文件里面所有的关系类型 */
        $relationshipTypes = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型
        foreach ($relationshipTypes as $relationshipTypeId => $relationshipTypeName) {
            $result['relationshipList'][] = ['relationshipId' => $relationshipTypeId, 'relationshipName' => $relationshipTypeName];
        }

        $this->Success($result);
    }

    /**   
    * 通讯录
    *  
    * @author weinengyu
    *
    * @return json
    */
    public function actionAddressBook()
    {        
        $userId = $this->checkUserToken();

        $result = ['addressBook' => []];

        /* 读取配置文件里面所有的关系类型 */
        $relationshipTypes = isset(yii::$app->params['relationshipType'])? yii::$app->params['relationshipType']: []; // 关系类型

        /* 查询该用户邦定学生的所有班级 */
        $classQuery = new \yii\db\Query();
        $classs = $classQuery->select([
                            'bc.id AS classId',
                            'bc.name AS className',
                            'bc.head_teacher_id AS head_teacher_id',
                        ])
                    ->from(UserStudent::tableName() . ' AS us')
                    ->leftJoin(Student::tableName() . ' AS s', 'us.student_id=s.id')
                    ->leftJoin(BClass::tableName() . ' AS bc', 's.class_id=bc.id')
                    ->where('us.user_id=:user_id')
                    ->params([':user_id' => $userId])
                    ->groupBy('classId')
                    ->all();

        foreach ($classs as $class) {
            $tempAddressBook = [];
            $tempAddressBook['classId'] = $class['classId'];
            $tempAddressBook['className'] = $class['className'];
            $tempAddressBook['teacherList'] = [];

            /* 当前班级班主任的tacherId */
            $headTeacherId = $class['head_teacher_id'];

            /* 查询当前班级正在用的课程表 */
            $classCurricula = ClassCurricula::find()->where('class_id=:class_id AND is_active=1')->params([':class_id' => $class['classId']])->one();
            if ($classCurricula) {
                $tempTeacherData = [];

                /* 查询该班级下面所有的任课老师 */
                $teaCurriSubjects = TeaCurriSubject::find()->where('curricula_id=:curricula_id AND teacher_id>0 AND subject_id>0')->params([':curricula_id' => $classCurricula->id])->groupBy('teacher_id, subject_id')->all();
                foreach ($teaCurriSubjects as $teaCurriSubject) {
                    /* 初始化老师数据 */
                    if ( ! isset($tempTeacherData[$teaCurriSubject->teacher_id])) {
                        $tempTeacherData[$teaCurriSubject->teacher_id] = [];

                        /* 初始化科目 */
                        $tempTeacherData[$teaCurriSubject->teacher_id]['subjects'] = [];
                        
                        /* 查询老师的数据 */
                        $tempTeacherModel = Teacher::findOne($teaCurriSubject->teacher_id);

                        $tempTeacherData[$teaCurriSubject->teacher_id]['name'] = $tempTeacherModel? $tempTeacherModel->name: ''; // 老师名称

                        /* 查询老师对应账号信息 */
                        $teacherUserModel = User::find()->where('teacher_id=:teacher_id AND is_family=0')->params([':teacher_id' => $teaCurriSubject->teacher_id])->one();

                        $tempTeacherData[$teaCurriSubject->teacher_id]['avator'] = $teacherUserModel? $teacherUserModel->small_avator: ''; // 老师头像
                        $tempTeacherData[$teaCurriSubject->teacher_id]['userId'] = $teacherUserModel? $teacherUserModel->id: 0; // 用户Id
                        $tempTeacherData[$teaCurriSubject->teacher_id]['phone'] = $teacherUserModel? $teacherUserModel->phone: ''; // 老师手机号码

                        /* 判断该班级班主任是否是该老师 */
                        $tempTeacherData[$teaCurriSubject->teacher_id]['isHeadTeacher'] = $headTeacherId == $teaCurriSubject->teacher_id? true: false; // 是否是班主任
                    }

                    /* 老师下所有的科目 */
                    $subjectModel = Subject::findOne($teaCurriSubject->subject_id);
                    $tempTeacherData[$teaCurriSubject->teacher_id]['subjects'][] = ['subjectId' => $teaCurriSubject->subject_id, 'subjectName' => $subjectModel? $subjectModel->name: ''];
                }

                /* 处理班级主任信息 */
                if ( $headTeacherId > 0 && ! isset($tempTeacherData[$headTeacherId])) {
                    $tempTeacherData[$headTeacherId] = [];

                    /* 初始化科目 */
                    $tempTeacherData[$headTeacherId]['subjects'] = [];
                    
                    /* 查询班主任的数据 */
                    $tempTeacherModel = Teacher::findOne($headTeacherId);

                    $tempTeacherData[$headTeacherId]['name'] = $tempTeacherModel? $tempTeacherModel->name: ''; // 班主任名称

                    /* 查询班主任对应账号信息 */
                    $teacherUserModel = User::find()->where('teacher_id=:teacher_id AND is_family=0')->params([':teacher_id' => $headTeacherId])->one();

                    $tempTeacherData[$headTeacherId]['avator'] = $teacherUserModel? $teacherUserModel->small_avator: ''; // 班主任头像
                    $tempTeacherData[$headTeacherId]['userId'] = $teacherUserModel? $teacherUserModel->id: 0; // 班主任用户Id
                    $tempTeacherData[$headTeacherId]['phone'] = $teacherUserModel? $teacherUserModel->phone: ''; // 班主任手机号码
                    $tempTeacherData[$headTeacherId]['isHeadTeacher'] = true; // 是班主任
                }

                $tempAddressBook['teacherList'] = $tempTeacherData;
            }

            /* 处理家长信息 */
            $tempAddressBook['familyList'] = [];

            /* 查询该班级下面所有的学生 */
            $students = Student::find()->where('class_id=:class_id')->params([':class_id' => $class['classId']])->having('status in(2,3,5)')->all();
            
            /* 查询学生所有的家长 */
            foreach ($students as $student) {
                $userStudents = UserStudent::find()->where('student_id=:student_id')->params([':student_id' => $student->id])->all();

                /* 处理学生家长信息 */
                foreach ($userStudents as $userStudent) {
                    $tempFamily = [];
                    $tempFamily['name'] =  isset($relationshipTypes[$userStudent->relationship])? $student->name . '的' . $relationshipTypes[$userStudent->relationship]: $student->name; // 家长称呼

                    /* 查询对应的家长账号信息 */
                    $tempUserModel = User::findOne($userStudent->user_id);

                    $tempFamily['avator'] = $tempUserModel? $tempUserModel->small_avator: ''; // 家长头像
                    $tempFamily['studentId'] = $userStudent->student_id; // 学生ID
                    $tempFamily['phone'] = $tempUserModel && $tempUserModel->is_public_phone == 1? $tempUserModel->phone: ''; // 家长手机号码

                    $tempAddressBook['familyList'][] = $tempFamily;
                }
            }

            $result['addressBook'][] = $tempAddressBook;
        }

        $this->Success($result);
    }
    
}