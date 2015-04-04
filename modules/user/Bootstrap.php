<?php

namespace app\modules\user;

use yii\web\GroupUrlRule;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $urlRules = [
            '<action:(login|logout|register)>' => 'user/<action>',
            '<action:confirm>/<id:\d+>/<code:.+>' => 'user/confirm',
            '<id:\d+>' => 'user/profile',
        ];
        $configUrlRule = [
            'prefix'  => 'user',
            'rules'  => $urlRules
        ];

        $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);
    }
}