<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div style="display: inline-block; min-width: 360px">
        <h3><?= Html::encode($this->title) ?></h3>
        
        <table class="table table-striped table-bordered detail-view">
            <tr>
                <th class="text-right">Имя:</th>
                <td><?= $user->name ?></td>
            </tr>
            <tr>
                <th class="text-right">Логин:</th>
                <td><?= $user->login ?></td>
            </tr>
            <tr>
                <th class="text-right">Email:</th>
                <td><?= $user->email ?></td>
            </tr>
        </table>
    </div>
</div>
