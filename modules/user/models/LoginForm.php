<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    public function attributeLabels()
    {
        return [
            'login'      => 'Логин',
            'password'   => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }
    
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !\Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                $this->addError($attribute, 'Неправильный логин или пароль');
            }
        }
    }
    
    
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }
    
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByLoginOrEmail($this->login);
        }

        return $this->_user;
    }
}
