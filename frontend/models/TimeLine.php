<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_line".
 *
 * @property integer $post_id
 * @property integer $user_id
 * @property string $post_title
 * @property string $post
 * @property string $posted_time
 *
 * @property User $user
 */
class TimeLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'time_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['post'], 'string'],
            [['posted_time'], 'safe'],
            [['post_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'post_title' => 'Post Title',
            'post' => 'Post',
            'posted_time' => 'Posted Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
