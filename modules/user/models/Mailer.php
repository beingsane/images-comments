<?php

namespace app\modules\user\models;

use yii\helpers\Url;

class Mailer
{
    public $sender = 'no-reply@testsite.com';
    
    
    public function sendConfirmationMessage(User $user)
    {
        $mailer = \Yii::$app->mailer;

        $appConfig = \Yii::$app->params['settings'];
        // Yii::$app->urlManager->hostInfo;
        $registration_link = Url::to(['/user/confirm/'.$user->id.'/'.$user->registration_code], true);
        
        $subject = 'Регистрация на сайте '.$appConfig['name'];
        $body = '
            Вы зарегистрировались на сайте <a href="'.Url::to(['/']).'">'.$appConfig['name'].'</a>
            <br/>Для завершения регистрации пройдите по ссылке:
            <br/><a href="'.$registration_link.'">'.$registration_link.'</a>
        ';
        
        $res = $mailer->compose()
            ->setTo($user->email)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
        return $res;
    }
}