<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <?php if($confirmed) { ?>
        <div class="alert alert-success">
            Регистрация завершена
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            Неправильная ссылка для подтверждения регистрации
        </div>
    <?php } ?>
</div>
