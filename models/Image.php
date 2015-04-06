<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $path
 * @property string $description
 *
 * @property User $user
 * @property ImageComment[] $imageComments
 */
class Image extends \yii\db\ActiveRecord
{
    public static $galleryPath = 'www/gallery_images/'; 
    public static $thumbnailPath = 'www/gallery_thumbnails/'; 

    public static $galleryURL = '/gallery_images/'; 
    public static $thumbnailURL = '/gallery_thumbnails/'; 
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'path'], 'required'],
            [['user_id'], 'integer'],
            [['path'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'path' => 'Путь',
            'description' => 'Описание',
        ];
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUser() 
    { 
       return $this->hasOne(User::className(), ['id' => 'user_id']); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageComments()
    {
        return $this->hasMany(ImageComment::className(), ['image_id' => 'id'])->orderBy('id DESC');
    }
    
    public function getAvgRating()
    {
        $query = new \yii\db\Query();
        
        $row = $query
            ->select(['IFNULL(AVG(image_comment.rating), 0) AS avg_rating'])
            ->from('image_comment')
            ->where(['image_id' => $this->id])
            ->one();
        
        return number_format($row->avg_rating, 1);
    }
}
