<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $status
 * @property string $up_date
 * @property string $pic_name
 */
class Page extends \yii\db\ActiveRecord
{
//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'page';
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['bd_date'], 'required'],
//            [['bd_date'], 'safe'],
//            [['firstname', 'lastname', 'pic_name'], 'string', 'max' => 255]
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'page_id' => 'Page ID',
//            'firstname' => 'Firstname',
//            'lastname' => 'Lastname',
//            'bd_date' => 'Bd Date',
//            'pic_name' => 'Pic Name',
//        ];
//    }
}
