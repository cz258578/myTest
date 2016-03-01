<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property string $name
 * @property string $bloc_id
 * @property string $school_id
 * @property string $structure_id
 * @property integer $sex
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property integer $status
 * @property string $create_time
 */
class Teacher extends \common\models\Teacher
{
}
