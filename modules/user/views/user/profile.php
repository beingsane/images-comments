<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div style="display: inline-block">
        <h3><?= Html::encode($this->title) ?></h3>
        <div class="list-group">
            <div class="list-group-item">
                <div class="text-right" style="width: 80px; display: inline-block"><strong>Имя:</strong></div>
                <span style="padding-left: 8px"><?= $user->name ?></span>
            </div>
            <div class="list-group-item">
                <div class="text-right" style="width: 80px; display: inline-block"><strong>Логин:</strong></div>
                <span style="padding-left: 8px"><?= $user->login ?></span>
            </div>
            <div class="list-group-item">
                <div class="text-right" style="width: 80px; display: inline-block"><strong>Email:</strong></div>
                <span style="padding-left: 8px"><?= $user->email ?></span>
            </div>
        </div>
    </div>
</div>
