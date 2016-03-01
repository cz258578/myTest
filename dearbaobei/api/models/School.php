<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%school}}".
 *
 * @property string $id
 * @property string $name
 * @property string $first_termname
 * @property integer $first_term_month
 * @property string $last_termname
 * @property integer $last_term_month
 * @property integer $type
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property string $lng
 * @property string $lat
 * @property string $bloc_id
 * @property string $address
 * @property string $create_time
 */
class School extends \common\models\School
{
}
