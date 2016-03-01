<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%bloc_bespeak_memo}}".
 *
 * @property string $id
 * @property string $bespeak_id
 * @property string $admin_user_id
 * @property string $description
 * @property string $create_time
 */
class BlocBespeakMemo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bloc_bespeak_memo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bespeak_id', 'create_time'], 'required'],
            [['description'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'bespeak_id' => '集团预约id',
            'admin_user_id' => '录入人ID',
            'description' => '描述',
            'create_time' => '创建时间',
        ];
    }

    public function getBespeakMemoList($params){

        $bespeak_id = $params['bespeak_id'];

        //分页
        $currentPage = isset($params['page'])? (int)$params['page']: 0;
        $currentPage = $currentPage > 0 ? $currentPage - 1: 0;
        $pageSize = isset($params['rows'])? (int)$params['rows']: yii::$app->params['pageSize'];

        $query = BlocBespeakMemo::find()->from(BlocBespeakMemo::tableName().' as bbm')
            ->leftJoin(Admin::tableName().' as a','bbm.admin_user_id=a.id')
            ->select('bbm.*,a.name as admin_name')
            ->where(['bbm.bespeak_id'=>$bespeak_id])->orderBy('bbm.id DESC')
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'sort' => [],
            'pagination' => [
                'pagesize' => $pageSize,
                'page' => $currentPage
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
