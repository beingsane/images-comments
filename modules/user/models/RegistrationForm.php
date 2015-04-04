<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;

class RegistrationForm extends Model
{
    public $email;
    public $login;
    public $password;
    public $name;
    
    public function rules()
    {
        return [
            ['login', 'required'],
            ['login', 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => 'Этот логин уже занят'],
            ['login', 'string', 'min' => 3, 'max' => 30],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => 'Этот email уже занят'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            
            ['name', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'login' => 'Логин',
            'password' => 'Пароль',
            'name' => 'Имя',
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->setAttributes([
            'email' => $this->email,
            'login' => $this->login,
            'password' => $this->password,
            'name' => $this->name
        ]);

        return $user->register();
    }
}