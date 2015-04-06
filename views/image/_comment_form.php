<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ImageComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rating')->dropDownList([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]) ?>

    <?php if (\Yii::$app->user->isGuest) { ?>
        <?= $form->field($model, 'user_name')->textInput(['maxlength' => 1024]) ?>
        <?= $form->field($model, 'user_email')->textInput(['maxlength' => 100]) ?>
    <?php } ?>
    
    <?= $form->field($model, 'text')->textarea(['maxlength' => 2048]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить комментарий', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
