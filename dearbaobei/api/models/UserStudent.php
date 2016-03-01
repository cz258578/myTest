<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%user_student}}".
 *
 * @property string $user_id
 * @property string $student_id
 * @property string $relationship
 */
class UserStudent extends \common\models\UserStudent
{
    /**
     * 根据UserId和studentId获取账号的称呼
     * @param userId int 用户Id
     * @param studentId int 学生Id, 如果是老师账号studentId0
     * @param relationshipTypes array 所有称呼的类型
     * @return array
     */
    public static function getUserInfo($userId, $studentId, $relationshipTypes = []) {        

        /* 所属账号信息 */
        $userModel = User::findOne($userId);
        $tempUserName = ''; // 用户名称
        $tempUserType = 1; // 用户类型, 1家长, 2老师
        
        if ($userModel) {

            if($userModel->teacher_id > 0) { // 账号属于老师
                /* 查询班主任的数据 */
                $tempTeacherModel = Teacher::findOne($userModel->teacher_id);
                $tempUserName = $tempTeacherModel? $tempTeacherModel->name . '老师': ''; // 老师的称呼
                $tempUserType = 2;
            } else { // 账号属于家长

                /* 查询家长跟学生的关系 */
                $userStudent = UserStudent::find()->where('user_id=:user_id AND student_id=:student_id')->params([':user_id' => $userModel->id, ':student_id' => $studentId])->one();
                $studentModel = Student::findOne($studentId);

                if ($userStudent && $studentModel) {
                    /* 家长称呼 */
                    $tempUserName = isset($relationshipTypes[$userStudent->relationship])? $studentModel->name . '的' . $relationshipTypes[$userStudent->relationship]: $studentModel->name;
                }
            }
        }

        return ['userName' => $tempUserName, 'userType' => $tempUserType];
    }
}
