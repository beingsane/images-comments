<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "image_comment".
 *
 * @property integer $id
 * @property integer $image_id
 * @property integer $rating
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_email
 * @property string $text
 * @property string $created_at
 *
 * @property Image $image
 * @property User $user
 */
class ImageComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'rating'], 'required'],
            [['image_id', 'rating', 'user_id'], 'integer'],
            [['user_name'], 'string', 'max' => 1024],
            [['user_email'], 'string', 'max' => 100],
            [['user_email'], 'email'],
            [['text'], 'string', 'max' => 2048],
            
            [['user_name', 'user_email'], 'required', 'when' => function($model) {
                return Yii::$app->user->isGuest;
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_id' => 'Image ID',
            'rating' => 'Рейтинг',
            'user_id' => 'User ID',
            'user_name' => 'Имя',
            'user_email' => 'Email',
            'text' => 'Текст',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function beforeSave($insert)
    {
        if ($this->image_id == 0)
        {
            return false;
        }
        
        if (Yii::$app->user->isGuest)
        {
            if(!$this->user_name || !$this->user_email)
            {
                return false;
            }
            
            $this->user_id = null;
        }
        else
        {
            $this->user_id = Yii::$app->user->identity->id;
            $this->user_name = null;
            $this->user_email = null;
        }
        
        return parent::beforeSave($insert);
    }
}
