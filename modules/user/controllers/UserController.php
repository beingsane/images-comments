<?php

namespace app\modules\user\controllers;

use Yii;
use yii\web\Controller;
use app\modules\user\models\LoginForm;
use app\modules\user\models\RegistrationForm;
use app\modules\user\models\User;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'register'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['logout', 'profile'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['confirm'], 'roles' => ['?', '@']],
                ]
            ],
        ];
    }
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionLogout()
    {
        \Yii::$app->getUser()->logout();
        return $this->goHome();
    }
    
    public function actionRegister()
    {
        $model = new RegistrationForm();

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return $this->render('register-success');
        }

        return $this->render('register', [
            'model'  => $model,
        ]);
    }
    
    public function actionConfirm($id, $code)
    {
        $id = (int)$id;
        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException;
        }

        $confirmed = $user->confirmRegistration($code);
        return $this->render('confirm', [
            'confirmed' => $confirmed
        ]);
    }
    
    public function actionProfile($id)
    {
        $user = User::findOne($id);
        return $this->render('profile', ['user' => $user]);
    }    
}
