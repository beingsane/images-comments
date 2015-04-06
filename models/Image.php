<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;
use yii\web\UploadedFile;

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
    
    public static $thumbnailWidth = 140;
    public static $thumbnailHeight = 100;
    
    
    /**
     * Загружаемый файл
     */
    public $image_file;
    
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
            [['user_id', 'path'], 'safe'],
            [['user_id'], 'integer'],
            [['path'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 1024],
            
            [['image_file'], 'file'],
            [['image_file'], 'image', 'extensions'=>'jpg, gif, png', 'maxWidth' => 2048, 'maxHeight' => 2048],
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
            'image_file' => 'Файл',
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
        
        return number_format($row['avg_rating'], 1);
    }
    
    public function checkImage($attribute, $params)
    {
        /*
        $image = UploadedFile::getInstance($this, 'image_file');
        if (!$image)
        {
            $this->addError($attribute, 'Произошла ошибка при загрузке файла');
            return false;
        }
        Image::thumbnail('@webroot/img/test-image.jpg', 120, 120)
            ->save(Yii::getAlias('@runtime/thumb-test-image.jpg'), ['quality' => 50]);
        $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
        */
    }
    
    public function beforeSave($insert)
    {
        $file = UploadedFile::getInstance($this, 'image_file');
        if(!$file)
        {
            $this->addError('image_file', 'Произошла ошибка при загрузке файла');
            return false;
        }
        
        $user = Yii::$app->user->identity;
        $randomName = Yii::$app->security->generateRandomString(10);
        // сохраняем загруженные изображения в отдельные папки пользователей,
        // для увеличения производительности файловой системы
        $path = $user->id .'/' .$randomName .'.' .$file->extension;
        
        $fullPath = Yii::getAlias('@app') .'/' .self::$galleryPath .$path;
        if(!is_dir(dirname($fullPath)))
        {
            mkdir(dirname($fullPath));
        }
        
        $saved = $file->saveAs($fullPath);
        if(!$saved)
        {
            $this->addError('image_file', 'Произошла ошибка при загрузке файла');
            return false;
        }
        
        
        $thumbFullPath = Yii::getAlias('@app') .'/' .self::$thumbnailPath .$path;
        if(!is_dir(dirname($thumbFullPath)))
        {
            mkdir(dirname($thumbFullPath));
        }
        $saved = \yii\imagine\Image::thumbnail($fullPath, self::$thumbnailWidth, self::$thumbnailHeight)
            ->save($thumbFullPath, ['quality' => 100]);
        
        if(!$saved)
        {
            $this->addError('image_file', 'Произошла ошибка при загрузке файла');
            return false;
        }
        
        
        $this->user_id = $user->id;
        $this->path = $path;
        return parent::beforeSave($insert);
    }
}
