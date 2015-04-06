<?php

namespace app\modules\user\models;

use Yii;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \yii\base\NotSupportedException;
use app\modules\user\models\Mailer;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $name
 * @property string $registration_code
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $i = 0;
        return [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 1024],
            [['email'], 'unique'],
            [['password'], 'safe'],
            [['registration_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Имя',
            'password_hash' => 'Password Hash',
            'registration_code' => 'Registration Code',
        ];
    }
    
    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->one();
    }
    
    
    public function register()
    {
        $this->registration_code = \Yii::$app->security->generateRandomString(20);
        
        if ($this->save())
        {
            $mailer = new Mailer();
            $mailer->sendConfirmationMessage($this);
            return true;
        }

        return false;
    }
    
    public function confirmRegistration($code)
    {
        if(!$code || $this->registration_code != $code)
        {
            return false;
        }
        
        $this->registration_code = '';
        $res = $this->save();
        if($res)
        {
            \Yii::$app->user->login($this);
        }
        return $res;
    }
    
    
    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->setAttribute('password_hash', \Yii::$app->security->generatePasswordHash($this->password));
        }

        return parent::beforeSave($insert);
    }
    
    
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public function getAuthKey()
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
}
